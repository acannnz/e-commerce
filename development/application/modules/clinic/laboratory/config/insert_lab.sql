Select StatusBayar,ProsesPayment,CloseAdmin from SIMtrRegistrasi where NoReg='181030REG-000561' 

SELECT MAX(right([NoBukti],6)) AS MyID FROM [SIMtrRJ] WHERE LEN([NoBukti])=16 AND RIGHT(LEFT(LTRIM([NoBukti]),4),2)='10' AND LEFT(LTRIM([NoBukti]),2)='19' AND RIGHT(LEFT(LTRIM([NoBukti]),9),3)='LAB'

INSERT INTO SIMtrRJ (NoBukti, Tanggal, Jam, NRM, NamaPasien, JenisKelamin, Umur_TH, Umur_Bln, Umur_Hr, Keterangan, Accept, DokterID, AnalisID, JenisPasien, RawatInap, LokasiPasien, RegNo, PakeReg, KamarNo, SectionID, adahasil, Rujukan, SectionAsalID,UserID,KdKelas,nomor,PPN,MCU,NoMCU,SupplierPengirimID,TransferSectionID, TransferDokterID,JenisKerjasamaID,DirujukVendorID,Dirujuk,KateriPlafon,NamaKasus,SudahTerpakai,NilaiPlafon,TerpakaiRIPerTahun,TerpakaiRIPerOpname,PlafonRIPerTahun,PlafonRIPerOpname,PlafonRINominal,TerpakaiNominalRI,Cito,RujukanDariVendorID,KeteranganPemeriksaan,TglLahir,CustomerKerjasamaID,Diagnosa,AlasanDiRujukID,TglKirim,TglTerima,TglInput) VALUES ('191009LAB-000001' ,'2019-10-09' ,'2019-10-09 15:50:25' ,'00.00.07' ,'AYUNDA MAYSANINERUM' ,'F' ,6 ,4 ,10 ,'' ,0 ,'DOK-001' ,'DOK-003' ,'' ,0 ,'' ,'181030REG-000561' ,0 ,'' ,'SEC005' ,0 ,0 ,'SEC000',1876,'xx',1,0,0,'','DOK-002','SEC007','DOK-001',3,'',0,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'','Vaksinasi Bio Td (1dose/ampul) di Poliklinik Umum','2013-05-29',0,'','','2019-10-09','2019-10-09','2019-10-09')

EXEC UpdateKunjunganPasien '00.00.07','2019-10-09 15:50:25','SEC005','DOK-001'

UPDATE SIMtrDataRegPasien set SudahPeriksa=1,NoBill='191009LAB-000001',TglPeriksa='2019-10-09' , 
jamPeriksa='2019-10-09 11:54:58' where NoReg='181030REG-000561' and nomor=1 and SectionID='SEC005'

UPDATE SIMtrRegistrasi set StatusPeriksa='Sudah' where NoReg='181030REG-000561'

UPDATE SIMtrMCU set SudahPeriksa=1 where NoBukti=''

Select max(nomor) from SIMtrDataRegPasien where NoReg='181030REG-000561'

Select max(NoAntri) from SIMtrDataRegPasien where SectionID='SEC007' and DokterID='DOK-001' and  Tanggal='2019-10-09'

INSERT into SIMtrDataRegPasien(NoReg,Nomor,Tanggal,Jam,SectionAsalID,SectionID,KelasID,DokterID,NoAntri,SUdahPeriksa,
RJ,KelasAsalID,Titip,JenisPasienID,UmurThn,UmurBln,UmurHr) VALUES
('181030REG-000561',2,'2019-10-09','2019-10-09 11:54:58','SEC005','SEC007','xx','DOK-001',1,0,1,'xx',0,3, 6 , 4, 10)

EXEC InsertUserActivities '2019-10-09','2019-10-09 11:54:58',1876,'00.00.07','Input BILLING.#181030REG-000561# NRM #00.00.07#191009LAB-000001','SIMtrRJ'

INSERT INTO SIMtrRJTransaksi (Qty, Tarif, Keterangan,DokterID, NoBukti, JasaID,HonorDokter,Pajak,THT,PPN,Plafon,Harga_KelebihanPlafon,KelasID,JenisKerjasamaID,KomisiDirujuk,KSO,MarkUp,Cito,Disc,KeteranganKelebihanPlafon,PasienKTP,ListHargaID,TarifOrig,CustomerKerjasamaID,Nomor,HargaPokok,DiskonTdkLangsung,DokterBacaID,TglBaca) VALUES (1 ,125000 ,'','XX' ,'191009LAB-000001' ,'JAS0039',0,0,0,0,NULL,0,'xx',3,0,0,0,0,0,'',1,40,NULL,0,2,0,0,'',NULL)

select JasaID from SIMmJasaBHP where JasaID='JAS0039'

EXEC InsertUserActivities '2019-10-09','2019-10-09 15:50:25',1876,'191009LAB-000001','INPUT DETAIL BILLING.#NRM #00.00.07#191009LAB-000001#JAS0039','SIMtrRJTransaksi'

INSERT INTO SIMtrRJTransaksiDetail (Prosentase, Harga, Akun_No, KelompokAkun, PostinganKe, Pajak, THT, NoBukti, JasaID, KomponenID,NilaiPersen,PajakTitipan,Insentif,InclInsentif,HargaOrig,MarkupProsen_MA,Nomor,DiskonTdkLangsung) VALUES (0 ,25000 ,'' ,'Biaya' ,'Hutang' ,0 ,0 ,'191009LAB-000001' ,'JAS0039' ,'DT01' ,0,0,0,0,25000,0,2,0)

INSERT INTO SIMtrRJTransaksiDetail (Prosentase, Harga, Akun_No, KelompokAkun, PostinganKe, Pajak, THT, NoBukti, JasaID, KomponenID,NilaiPersen,PajakTitipan,Insentif,InclInsentif,HargaOrig,MarkupProsen_MA,Nomor,DiskonTdkLangsung) VALUES (0 ,5000 ,'' ,'Pendapatan' ,'GL' ,0 ,0 ,'191009LAB-000001' ,'JAS0039' ,'DT03' ,0,0,0,0,5000,0,2,0)

INSERT INTO SIMtrRJTransaksiDetail (Prosentase, Harga, Akun_No, KelompokAkun, PostinganKe, Pajak, THT, NoBukti, JasaID, KomponenID,NilaiPersen,PajakTitipan,Insentif,InclInsentif,HargaOrig,MarkupProsen_MA,Nomor,DiskonTdkLangsung) VALUES (0 ,95000 ,'' ,'Biaya' ,'GL' ,0 ,0 ,'191009LAB-000001' ,'JAS0039' ,'DT51' ,0,0,0,0,95000,0,2,0)
