Select StatusBayar,ProsesPayment,CloseAdmin from SIMtrRegistrasi where NoReg='181030REG-000561' 

UPDATE SIMtrRJ  SET 
	NoBukti='191009LAB-000001' ,
	Tanggal='2019-10-09' ,
	Jam='2019-10-09 15:50:25' 
	,NRM='00.00.07' ,
	NamaPasien='AYUNDA MAYSANINERUM' ,
	JenisKelamin='F' ,
	Umur_TH=6 ,
	Umur_Bln=4 ,
	Umur_Hr=10 ,
	Keterangan='' ,
	Accept=0 ,
	DokterID='DOK-001' ,
	AnalisID='DOK-003' ,
	JenisPasien='' ,
	RawatInap=0,
	JenisKerjasamaID=3 ,
	LokasiPasien='' ,
	RegNo='181030REG-000561' ,
	PakeReg=0 ,
	KamarNo='',
	SupplierPengirimID='DOK-002' ,
	SectionID='SEC005' ,
	adahasil=0,
	NoMCU='' ,
	Rujukan=0,MCU=0 ,
	SectionAsalID='SEC000',
	UserID=1876,
	KdKelas='xx',
	nomor=1,
	PPN=0,
	TransferSectionID='SEC007',
	TransferDokterID='DOK-001',
	DirujukVendorID='',
	Dirujuk=0,
	KateriPlafon='',
	NamaKasus='',
	SudahTerpakai=0,
	NilaiPlafon=0,
	TerpakaiRIPerTahun=NULL,
	TerpakaiRIPerOpname=NULL,
	PlafonRIPerTahun=NULL,
	PlafonRIPerOpname=NULL,
	PlafonRINominal=0,
	TerpakaiNominalRI=0,
	Cito=0,
	RujukanDariVendorID='',
	KeteranganPemeriksaan='Vaksinasi Bio Td (1dose/ampul) di Poliklinik Umum',
	TglLahir='2013-05-29',
	AlasanDiRujukID='',
	TglKirim='2019-10-09',
	TglTerima='2019-10-09' 
WHERE NoBukti='191009LAB-000001'

UPDATE SIMtrRegistrasi set StatusPeriksa='Sudah' where NoReg='181030REG-000561'

Select max(nomor) from SIMtrDataRegPasien where NoReg='181030REG-000561'

Select max(NoAntri) from SIMtrDataRegPasien where SectionID='SEC007' and DokterID='DOK-001' and  Tanggal='2019-10-11'

EXEC InsertUserActivities '2019-10-09','2019-10-09 15:50:25',1876,'00.00.07','EDIT BILLING.#181030REG-000561# NRM #00.00.07#191009LAB-000001','SIMtrRJ'

UPDATE SIMtrRJTransaksi  SET Qty=2 ,Tarif=125000 ,Keterangan='',DokterID='XX' ,NoBukti='191009LAB-000001' ,JasaID='JAS0039',HonorDokter=0,Pajak=0,THT=0,PPN=0,Plafon=0,Harga_KelebihanPlafon=0,KElasID='xx',JenisKerjasamaID=3,KomisiDirujuk=0,KSO=0,MarkUp=0,Cito=0,Disc=0,KeteranganKelebihanPlafon='',PasienKTP=1,ListHargaID=40,TarifOrig=125000,DiskonTdkLangsung=0,DokterBacaID='',TglBaca=NULL WHERE NoBukti='191009LAB-000001' AND JasaID='JAS0039' and Nomor=2

EXEC InsertUserActivities '2019-10-11','2019-10-11 13:43:04',1876,'191009LAB-000001','EDIT DETAIL BILLING.#NRM ##191009LAB-000001#JAS0039#ORIG125,000.00#CUR125,000.00','SIMtrRJTransaksi'
