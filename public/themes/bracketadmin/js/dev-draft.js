/**
 * Autosave Module
 * 
 * Menyimpan draft form ke localStorage dengan enkripsi AES-256.
 * Mencegah data hilang saat refresh, device mati, atau internet terputus.
 * 
 * Cara penggunaan:
 * 1. Tambahkan class "draft-autosave" pada tag <form>
 * 2. (Opsional) Tambahkan data-patient-id atau data-no-rm untuk context-aware storage
 * 3. (Opsional) Tambahkan data-autosave-exclude="field1,field2" untuk exclude field tertentu
 * 
 * Contoh:
 * <form id="form-soap" class="draft-autosave" data-patient-id="<?= $no_rm ?>">
 */

(function($) {
    'use strict';

    // Encryption secret key (kombinasi dengan user_id untuk keamanan)
    var SECRET_KEY = 'METTA_DRAFT_AUTOSAVE_2024_' + (window.AUTOSAVE_CONFIG ? AUTOSAVE_CONFIG.user_id : 'default');
    
    // Konfigurasi
    var CONFIG = {
        debounceDelay: 2000, // Delay 2 detik sebelum save
        expiryMinutes: window.AUTOSAVE_CONFIG ? AUTOSAVE_CONFIG.autosave_expiry_minutes : 60,
        csrfTokenName: window.AUTOSAVE_CONFIG ? AUTOSAVE_CONFIG.csrf_token_name : 'csrf_test_name',
        storagePrefix: 'draft_'
    };

    /**
     * Enkripsi data menggunakan AES-256
     */
    function encrypt(data) {
        try {
            if (typeof CryptoJS === 'undefined') {
                //console.warn('[Autosave] CryptoJS not loaded, saving plain data');
                return JSON.stringify(data);
            }
            return CryptoJS.AES.encrypt(JSON.stringify(data), SECRET_KEY).toString();
        } catch (e) {
            //console.error('[Autosave] Encryption error:', e);
            return null;
        }
    }

    /**
     * Dekripsi data
     */
    function decrypt(encryptedData) {
        try {
            if (typeof CryptoJS === 'undefined') {
                return JSON.parse(encryptedData);
            }
            var bytes = CryptoJS.AES.decrypt(encryptedData, SECRET_KEY);
            var decryptedString = bytes.toString(CryptoJS.enc.Utf8);
            return JSON.parse(decryptedString);
        } catch (e) {
            //console.error('[Autosave] Decryption error:', e);
            return null;
        }
    }

    /**
     * Generate storage key berdasarkan context
     * Format: draft_[USER_ID]_[FORM_ID]_[CONTEXT_ID]
     */
    function getStorageKey(form) {
        var userId = window.AUTOSAVE_CONFIG ? AUTOSAVE_CONFIG.user_id : 'guest';
        var formId = form.attr('id') || form.attr('data-form-id') || 'default';
        var contextId = form.attr('data-patient-id') || 
                        form.attr('data-no-rm') || 
                        form.attr('data-context-id') ||
                        window.location.pathname.replace(/\//g, '_');
        
        return CONFIG.storagePrefix + userId + '_' + formId + '_' + contextId;
    }

    /**
     * Get exclude fields dari form attribute
     */
    function getExcludeFields(form) {
        var excludeAttr = form.attr('data-autosave-exclude') || '';
        var excludeFields = excludeAttr.split(',').map(function(s) { return s.trim(); });
        
        // Selalu exclude CSRF token
        excludeFields.push(CONFIG.csrfTokenName);
        
        return excludeFields.filter(function(f) { return f.length > 0; });
    }

    /**
     * Serialize form data (termasuk radio, checkbox, select)
     */
    function serializeFormData(form, excludeFields) {
        var data = {};
        
        // Serialize input, select, textarea
        form.find('input:not([type="submit"]):not([type="button"]), select, textarea').each(function() {
            var $el = $(this);
            var name = $el.attr('name');
            var value = $el.val();

            if (!name || excludeFields.indexOf(name) === -1) {
                data[name] = value;
            }
        });

        // Checkbox (bisa multiple dengan nama sama)
        form.find('input[type="checkbox"]').each(function() {
            var name = $(this).attr('name');
            if (name && excludeFields.indexOf(name) === -1) {
                if (!data[name]) {
                    data[name] = [];
                }
                if ($(this).is(':checked')) {
                    data[name].push($(this).val());
                }
            }
        });

        // Radio button
        form.find('input[type="radio"]:checked').each(function() {
            var name = $(this).attr('name');
            if (name && excludeFields.indexOf(name) === -1) {
                data[name] = $(this).val();
            }
        });

        // Select (termasuk select2)
        form.find('select').each(function() {
            var name = $(this).attr('name');
            if (name && excludeFields.indexOf(name) === -1) {
                data[name] = $(this).val();
            }
        });

        // Textarea
        form.find('textarea').each(function() {
            var name = $(this).attr('name');
            if (name && excludeFields.indexOf(name) === -1) {
                data[name] = $(this).val();
            }
        });

        // Auto-serialize DataTables
        form.find('table.dataTable').each(function() {
            var tableId = $(this).attr('id');
            if (tableId) {
                try {
                    var dt = $(this).DataTable();
                    data['dt_auto_' + tableId] = dt.rows().data().toArray();
                } catch (e) {
                    console.warn('[Autosave] Failed to serialize DataTable:', tableId, e);
                }
            }
        });

        // Auto-serialize data-* attributes (e.g. data-product, data-info, etc)
        // Ini membuat semua input dengan data-* attributes otomatis tersimpan
        form.find('input[data-product], input[data-info], input[data-object], select[data-product], select[data-info], select[data-object]').each(function() {
            var $el = $(this);
            var name = $el.attr('name');
            if (!name || excludeFields.indexOf(name) !== -1) return;
            
            // Cari semua data-* attributes yang sudah di-set via .data()
            var jqData = $el.data(); // Get all data set via jQuery
            
            $.each(jqData, function(dataKey, dataValue) {
                // Skip jika value kosong atau undefined
                if (dataValue === undefined || dataValue === null) return;
                
                // Skip jika value masih default dari HTML attribute (string kosong atau "{}")
                if (typeof dataValue === 'string' && (dataValue === '' || dataValue === '{}')) return;
                
                // Skip jika object kosong {}
                if (typeof dataValue === 'object' && !Array.isArray(dataValue) && Object.keys(dataValue).length === 0) return;
                
                // Simpan sebagai JSON string jika object/array
                if (typeof dataValue === 'object' || Array.isArray(dataValue)) {
                    data['_data_' + name + '_' + dataKey] = JSON.stringify(dataValue);
                } else {
                    data['_data_' + name + '_' + dataKey] = dataValue;
                }
            });
        });

        // Custom Serializers
        customSerializers.forEach(function(serializer) {
            try {
                var customData = serializer(form);
                if (typeof customData === 'object') {
                    $.extend(data, customData);
                }
            } catch (e) {
                console.error('[Autosave] Custom serializer error:', e);
            }
        });
        
        return data;
    }

    /**
     * Restore form data dari draft
     */
    function restoreFormData(form, data) {
        $.each(data, function(name, value) {
            // Handle Auto-restore DataTables
            if (name.indexOf('dt_auto_') === 0 && Array.isArray(value)) {
                var tableId = name.replace('dt_auto_', '');
                var table = form.find('#' + tableId);
                if (table.length && $.fn.DataTable.isDataTable(table)) {
                    try {
                        var dt = table.DataTable();
                        dt.clear();
                        if (value.length > 0) {
                            dt.rows.add(value);
                            dt.draw();
                        }
                        //console.log('[Autosave] Restored DataTable:', tableId);
                    } catch (e) {
                        console.warn('[Autosave] Failed to restore DataTable:', tableId, e);
                    }
                }
                return; // Continue
            }

            var field = form.find('[name="' + name + '"]');
            
            if (field.length === 0) return;

            // Handle checkbox
            if (field.attr('type') === 'checkbox') {
                form.find('[name="' + name + '"]').prop('checked', false);
                if (Array.isArray(value)) {
                    value.forEach(function(v) {
                        form.find('[name="' + name + '"][value="' + v + '"]').prop('checked', true);
                    });
                }
            }
            // Handle radio
            else if (field.attr('type') === 'radio') {
                form.find('[name="' + name + '"][value="' + value + '"]').prop('checked', true);
            }
            // Handle select (termasuk select2)
            else if (field.is('select')) {
                field.val(value);
                // Trigger change untuk select2
                if (field.hasClass('select2') || field.hasClass('select2-prj') || field.hasClass('select2-ptm')) {
                    field.trigger('change');
                }
            } else {
                // Handle normal input/select/textarea
                field.val(value);
            }
        });

        // Auto-restore data-* attributes
        $.each(data, function(key, value) {
            if (key.indexOf('_data_') === 0) {
                // Format: _data_[fieldname]_[datakey]
                var parts = key.replace('_data_', '').split('_');
                if (parts.length >= 2) {
                    var dataKey = parts.pop(); // Last part is dataKey
                    var fieldName = parts.join('_'); // Rest is fieldName
                    var $field = form.find('[name="' + fieldName + '"]');
                    
                    if ($field.length) {
                        try {
                            // Parse JSON jika berupa string object
                            var parsedValue = value;
                            if (typeof value === 'string' && (value.startsWith('{') || value.startsWith('['))) {
                                parsedValue = JSON.parse(value);
                            }
                            $field.data(dataKey, parsedValue);
                            //console.log('[Autosave] Restored data-' + dataKey + ' for field:', fieldName);
                        } catch (e) {
                            console.warn('[Autosave] Failed to restore data-' + dataKey + ':', e);
                        }
                    }
                }
                return; // Skip ke iterasi berikutnya
            }
        });

        // Custom Restorers
        customRestorers.forEach(function(restorer) {
            try {
                restorer(form, data);
            } catch (e) {
                console.error('[Autosave] Custom restorer error:', e);
            }
        });
    }

    /**
     * Save draft ke localStorage
     */
    function saveDraft(form) {
        // Mencegah save jika sedang proses submit
        if (form.data('autosave-submitting')) return;

        var key = getStorageKey(form);
        var excludeFields = getExcludeFields(form);
        var formData = serializeFormData(form, excludeFields);
        
        // Cek apakah ada data yang diisi
        var hasData = Object.keys(formData).some(function(k) {
            var val = formData[k];
            if (Array.isArray(val)) return val.length > 0;
            return val && val.toString().trim().length > 0;
        });

        if (!hasData) {
            // Hapus draft jika form kosong
            localStorage.removeItem(key);
            return;
        }

        var draftData = {
            data: formData,
            timestamp: new Date().getTime(),
            formId: form.attr('id') || 'unknown'
        };

        var encrypted = encrypt(draftData);
        if (encrypted) {
            localStorage.setItem(key, encrypted);
            //console.log('[Autosave] Draft saved:', key);
        }
    }

    /**
     * Load draft dari localStorage
     */
    function loadDraft(form) {
        var key = getStorageKey(form);
        var encrypted = localStorage.getItem(key);
        
        if (!encrypted) return null;

        var draftData = decrypt(encrypted);
        if (!draftData) {
            // Data corrupt, hapus
            localStorage.removeItem(key);
            return null;
        }

        // Cek expiry
        var now = new Date().getTime();
        var expiryMs = CONFIG.expiryMinutes * 60 * 1000;
        
        if (now - draftData.timestamp > expiryMs) {
            // Data sudah expired
            localStorage.removeItem(key);
            //console.log('[Autosave] Draft expired, deleted:', key);
            return null;
        }

        return draftData;
    }

    /**
     * Hapus draft
     */
    function deleteDraft(form) {
        var key = getStorageKey(form);
        localStorage.removeItem(key);
        //console.log('[Autosave] Draft deleted:', key);
    }

    /**
     * Format waktu tersimpan
     */
    function formatTimestamp(timestamp) {
        var date = new Date(timestamp);
        var now = new Date();
        var diffMs = now - date;
        var diffMins = Math.floor(diffMs / 60000);
        
        if (diffMins < 1) return 'baru saja';
        if (diffMins < 60) return diffMins + ' menit yang lalu';
        
        var diffHours = Math.floor(diffMins / 60);
        if (diffHours < 24) return diffHours + ' jam yang lalu';
        
        return date.toLocaleDateString('id-ID') + ' ' + date.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
    }

    /**
     * Tampilkan popup konfirmasi restore menggunakan SweetAlert2
     */
    function showRestoreConfirmation(form, draftData) {
        var savedTime = formatTimestamp(draftData.timestamp);
        
        // Cek apakah SweetAlert2 tersedia
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Data Draft Ditemukan',
                html: '<p>Terdapat data yang belum tersimpan sejak <strong>' + savedTime + '</strong>.</p>' +
                      '<p>Apakah Anda ingin memulihkan data tersebut?</p>',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: '<i class="fa fa-check"></i> Restore',
                denyButtonText: '<i class="fa fa-trash"></i> Hapus Draft',
                confirmButtonColor: '#28a745',
                denyButtonColor: '#dc3545',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    // Restore data
                    restoreFormData(form, draftData.data);
                    Swal.fire({
                        title: 'Data Dipulihkan!',
                        text: 'Data draft berhasil dimuat ke form.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    // Field indicators sudah ditambahkan saat restore
                } else if (result.isDenied) {
                    // Hapus draft
                    deleteDraft(form);
                    Swal.fire({
                        title: 'Draft Dihapus',
                        text: 'Data draft telah dihapus.',
                        icon: 'info',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
                // Jika cancel, tidak melakukan apa-apa (draft tetap tersimpan)
            });
        } 
        // Fallback ke confirm() jika SweetAlert2 tidak tersedia
        else {
            var message = 'Ditemukan data yang belum tersimpan sejak : ' + savedTime + '.\n\n' +
                          'Klik OK untuk restore data, Cancel untuk abaikan.\n' +
                          '(Klik Cancel akan menghapus data draft ini permanen)';
            
            if (confirm(message)) {
                restoreFormData(form, draftData.data);
                if (typeof toastr !== 'undefined') {
                    toastr.success('Data draft berhasil dimuat ke form.');
                }
            } else {
                deleteDraft(form);
                if (typeof toastr !== 'undefined') {
                    toastr.info('Data draft dihapus.');
                }
            }
        }
    }

    /**
     * Initialize autosave untuk sebuah form
     */
    function initAutosave(form) {
        // Cegah double initialization
        if (form.data('autosave-initialized')) {
            //console.log('[Autosave] Already initialized for form:', form.attr('id') || 'unnamed');
            return;
        }
        form.data('autosave-initialized', true);

        var debounceTimer = null;
        var excludeFields = getExcludeFields(form);
        
        // Cek apakah ada draft yang tersimpan
        var draftData = loadDraft(form);
        if (draftData) {
            // Tampilkan popup konfirmasi (delay sedikit agar form sudah fully rendered)
            setTimeout(function() {
                showRestoreConfirmation(form, draftData);
            }, 500);
        }

        // Event listener untuk perubahan input
        form.on('input change', 'input, select, textarea', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                saveDraft(form);
            }, CONFIG.debounceDelay);
        });

        // Auto-detect DataTable changes (triggered by draw event)
        form.find('table.dataTable').each(function() {
            var tableId = $(this).attr('id');
            if (tableId) {
                var dt = $(this).DataTable();
                dt.on('draw.dt', function() {
                    // Check if table is visible (to avoid triggering on hidden initialization)
                    if ($(this).is(':visible')) {
                         clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(function() {
                            saveDraft(form);
                        }, 1000); // 1 sec delay for table changes
                    }
                });
            }
        });

        // Clear draft saat form submit sukses
        form.on('submit', function() {
            // Delay sedikit untuk memastikan submit berhasil
            setTimeout(function() {
                deleteDraft(form);
            }, 500);
        });

        // Juga handle click pada button submit (untuk form yang tidak menggunakan native submit)
        // Menggunakan event delegation agar berfungsi untuk elemen yang di-load via AJAX
        form.on('click', 'button[type="submit"], button[id*="submit"], a[id*="submit"], [id*="submit"]', function() {
            // Set flag bahwa ini adalah submit action
            form.data('autosave-submitting', true);
        });

        //console.log('[Autosave] Initialized for form:', form.attr('id') || 'unnamed');
    }

    /**
     * Scan dan initialize form baru
     */
    function scanAndInitForms() {
        // Debug: lihat semua form yang ada di DOM
        var allForms = $('form');
        //console.log('[Autosave] Total forms in DOM:', allForms.length);
        allForms.each(function(i) {
            //console.log('[Autosave] - Form', i, 'id:', $(this).attr('id'), 'class:', $(this).attr('class'));
        });
        
        var forms = $('form.draft-autosave');
        //console.log('[Autosave] Forms with class draft-autosave:', forms.length);
        forms.each(function() {
            var form = $(this);
            //console.log('[Autosave] Found form:', form.attr('id'), 'initialized:', form.data('autosave-initialized'));
            if (!form.data('autosave-initialized')) {
                initAutosave(form);
            }
        });
    }

    /**
     * Public API
     */
    var customSerializers = [];
    var customRestorers = [];

    window.MettaDraft = {
        // Register custom serializer (function returning object)
        registerSerializer: function(fn) {
            if (typeof fn === 'function') customSerializers.push(fn);
        },
        // Register custom restorer (function accepting data object)
        registerRestorer: function(fn) {
            if (typeof fn === 'function') customRestorers.push(fn);
        },
        // Manual init (untuk form yang di-load via AJAX)
        init: function(formSelector) {
            var form = $(formSelector);
            if (form.length && form.hasClass('draft-autosave')) {
                initAutosave(form);
            }
        },
        // Scan semua form dan init yang belum terinisialisasi
        scan: function() {
            scanAndInitForms();
        },
        // Manual save
        save: function(formSelector) {
            var form = $(formSelector);
            if (form.length) saveDraft(form);
        },
        // Manual restore
        restore: function(formSelector) {
            var form = $(formSelector);
            if (form.length) {
                var draftData = loadDraft(form);
                if (draftData) {
                    restoreFormData(form, draftData.data);
                    return true;
                }
            }
            return false;
        },
        // Manual delete
        delete: function(formSelector) {
            var form = $(formSelector);
            if (form.length) deleteDraft(form);
        },
        // Check if draft exists
        hasDraft: function(formSelector) {
            var form = $(formSelector);
            if (form.length) {
                return loadDraft(form) !== null;
            }
            return false;
        }
    };

    /**
     * Auto-initialize pada document ready
     */
    $(document).ready(function() {
        //console.log('[Autosave] Document ready, starting scan...');
        // Cari semua form dengan class "draft-autosave" yang sudah ada
        scanAndInitForms();

        // Setup MutationObserver untuk mendeteksi form yang di-load via AJAX
        if (typeof MutationObserver !== 'undefined') {
            var observer = new MutationObserver(function(mutations) {
                var shouldScan = false;
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length > 0) {
                        // Cek apakah ada form baru yang ditambahkan
                        mutation.addedNodes.forEach(function(node) {
                            if (node.nodeType === 1) { // Element node
                                if ($(node).is('form.draft-autosave') || 
                                    $(node).find('form.draft-autosave').length > 0) {
                                    shouldScan = true;
                                }
                            }
                        });
                    }
                });
                
                if (shouldScan) {
                    //console.log('[Autosave] MutationObserver detected new form, rescanning...');
                    // Delay sedikit untuk memastikan DOM sudah fully rendered
                    setTimeout(function() {
                        scanAndInitForms();
                    }, 100);
                }
            });

            // Observe seluruh body untuk perubahan
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });

            //console.log('[Autosave] MutationObserver active, watching for AJAX-loaded forms');
        } else {
            //console.warn('[Autosave] MutationObserver not supported, AJAX forms need manual init');
        }

        /**
         * Hook ke jQuery AJAX global untuk mendeteksi sukses submit
         * Akan clear draft ketika response.status === "success"
         */
        $(document).ajaxSuccess(function(event, xhr, settings) {
            try {
                var response = xhr.responseJSON || JSON.parse(xhr.responseText);
                
                // Cek apakah ini adalah response sukses (pattern umum di Simpus)
                if (response && response.status === 'success') {
                    // Cari form simpus-autosave yang ada di halaman
                    $('form.draft-autosave').each(function() {
                        var form = $(this);
                        // Cek apakah form ini baru saja di-submit (ada flag submitting)
                        if (form.data('autosave-submitting')) {
                            deleteDraft(form);
                            form.data('autosave-submitting', false);
                            //console.log('[Autosave] Draft cleared after successful AJAX submit');
                        }
                    });
                }
            } catch (e) {
                // Response bukan JSON, abaikan
            }
        });
    });

})(jQuery);
