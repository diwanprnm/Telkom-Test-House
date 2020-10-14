DELETE FROM menus;
INSERT INTO `menus` VALUES ('1', '0', 'Beranda', '', 'ti-home');
INSERT INTO `menus` VALUES ('2', '0', 'Pengujian', '#', 'ti-files');
INSERT INTO `menus` VALUES ('3', '2', 'Pengujian', 'examination', '');
INSERT INTO `menus` VALUES ('4', '2', 'Riwayat SPK', 'spk', '');
INSERT INTO `menus` VALUES ('5', '2', 'Riwayat Pengujian Selesai', 'examinationdone', '');
INSERT INTO `menus` VALUES ('6', '2', 'Perangkat Lulus Uji', 'device', '');
INSERT INTO `menus` VALUES ('7', '2', 'Perangkat Tidak Lulus Uji', 'devicenc', '');
INSERT INTO `menus` VALUES ('8', '2', 'Equipment', 'equipment', '');
INSERT INTO `menus` VALUES ('9', '2', 'Rekap Nomor SPB', 'spb', '');
INSERT INTO `menus` VALUES ('10', '2', 'Rekap Nomor Gudang', 'nogudang', '');
INSERT INTO `menus` VALUES ('11', '2', 'Rekap Uji Fungsi', 'functiontest', '');
INSERT INTO `menus` VALUES ('12', '2', 'Rekap Feedback dan Complaint', 'feedbackncomplaint', '');
INSERT INTO `menus` VALUES ('13', '2', 'Rekap Kuitansi dan Faktur Pajak', 'fakturpajak', '');
INSERT INTO `menus` VALUES ('14', '0', 'Dashboard', 'topdashboard', 'ti-dashboard');
INSERT INTO `menus` VALUES ('15', '0', 'Customer Relation', '#', 'ti-comments-smiley');
INSERT INTO `menus` VALUES ('16', '15', 'Questions and Answers (QnA)', 'feedback', 'ti-comments');
INSERT INTO `menus` VALUES ('17', '15', 'Testimonial', 'testimonial', 'ti-comment-alt');
INSERT INTO `menus` VALUES ('18', '15', 'FAQ', 'faq', '');
INSERT INTO `menus` VALUES ('19', '0', 'Data Master', '#', 'ti-server');
INSERT INTO `menus` VALUES ('20', '19', 'Artikel', 'article', '');
INSERT INTO `menus` VALUES ('21', '19', 'STEL/STD', 'stel', '');
INSERT INTO `menus` VALUES ('22', '19', 'Tarif Pengujian', 'charge', '');
INSERT INTO `menus` VALUES ('23', '19', 'Tarif Pengujian Baru', 'newcharge', '');
INSERT INTO `menus` VALUES ('24', '19', 'Tarif Kalibrasi', 'calibration', '');
INSERT INTO `menus` VALUES ('25', '19', 'Slideshow', 'slideshow', '');
INSERT INTO `menus` VALUES ('26', '19', 'Lab Pengujian', 'labs', '');
INSERT INTO `menus` VALUES ('27', '19', 'Perusahaan', 'company', '');
INSERT INTO `menus` VALUES ('28', '19', 'Permohonan edit Perusahaan', 'tempcompany', '');
INSERT INTO `menus` VALUES ('29', '19', 'Partners', 'footer', '');
INSERT INTO `menus` VALUES ('30', '19', 'Pertanyaan Kuisioner', 'questionerquestion', '');
INSERT INTO `menus` VALUES ('31', '19', 'Kategori Pertanyaan', 'question', '');
INSERT INTO `menus` VALUES ('32', '19', 'Pop Up Information', 'popupinformation', '');
INSERT INTO `menus` VALUES ('33', '19', 'Certification', 'certification', '');
INSERT INTO `menus` VALUES ('34', '0', 'Tools', '#', 'ti-settings');
INSERT INTO `menus` VALUES ('35', '34', 'Backup & Restore', 'backup', '');
INSERT INTO `menus` VALUES ('36', '34', 'Activity Log', 'log', '');
INSERT INTO `menus` VALUES ('37', '34', 'Kuitansi', 'kuitansi', '');
INSERT INTO `menus` VALUES ('38', '34', 'General Setting', 'generalSetting', '');
INSERT INTO `menus` VALUES ('39', '34', 'Administrator Log', 'log_administrator', '');
INSERT INTO `menus` VALUES ('40', '34', 'Video Tutorial', 'videoTutorial', '');
INSERT INTO `menus` VALUES ('41', '0', 'User & Role Management', '#', 'ti-hand-open');
INSERT INTO `menus` VALUES ('42', '41', 'User Role', 'role', '');
INSERT INTO `menus` VALUES ('43', '41', 'User Internal', 'userin', '');
INSERT INTO `menus` VALUES ('44', '41', 'User Eksternal', 'usereks', '');
INSERT INTO `menus` VALUES ('45', '41', 'Role Pengujian', 'privilege', '');
INSERT INTO `menus` VALUES ('46', '41', 'Role Pertanyaan', 'questionpriv', '');
INSERT INTO `menus` VALUES ('47', '0', 'Keuangan', '#', 'ti-money');
INSERT INTO `menus` VALUES ('48', '47', 'Rekap Pembelian STEL', 'sales', 'ti-money');
INSERT INTO `menus` VALUES ('49', '47', 'Rekap Pengujian Perangkat', 'income', 'ti-money');
INSERT INTO `menus` VALUES ('50', '0', 'Web Statistic', 'analytic', 'ti-pie-chart');

INSERT INTO `users_menus` VALUES (1, 1, '1', '1', '', '2017-6-20 00:04:45', '2017-6-20 00:04:45');
INSERT INTO `users_menus` VALUES (2, 2, '1', '1', '', '2017-6-20 00:04:46', '2017-6-20 00:04:46');
INSERT INTO `users_menus` VALUES (3, 3, '1', '1', '', '2017-6-20 00:04:46', '2017-6-20 00:04:46');
INSERT INTO `users_menus` VALUES (4, 4, '1', '1', '', '2017-6-20 00:04:47', '2017-6-20 00:04:47');
INSERT INTO `users_menus` VALUES (5, 5, '1', '1', '', '2017-6-20 00:04:47', '2017-6-20 00:04:47');
INSERT INTO `users_menus` VALUES (6, 6, '1', '1', '', '2017-6-20 00:04:48', '2017-6-20 00:04:48');
INSERT INTO `users_menus` VALUES (7, 7, '1', '1', '', '2017-6-20 00:04:49', '2017-6-20 00:04:49');
INSERT INTO `users_menus` VALUES (8, 8, '1', '1', '', '2017-6-20 00:04:50', '2017-6-20 00:04:50');
INSERT INTO `users_menus` VALUES (9, 9, '1', '1', '', '2017-6-20 00:04:50', '2017-6-20 00:04:50');
INSERT INTO `users_menus` VALUES (10, 10, '1', '1', '', '2017-6-20 00:04:51', '2017-6-20 00:04:51');
INSERT INTO `users_menus` VALUES (11, 11, '1', '1', '', '2017-6-20 00:04:57', '2017-6-20 00:04:57');
INSERT INTO `users_menus` VALUES (12, 12, '1', '1', '', '2017-6-20 00:05:00', '2017-6-20 00:05:00');
INSERT INTO `users_menus` VALUES (13, 13, '1', '1', '', '2017-6-20 00:05:03', '2017-6-20 00:05:03');
INSERT INTO `users_menus` VALUES (14, 14, '1', '1', '', '2017-6-20 00:05:03', '2017-6-20 00:05:03');
INSERT INTO `users_menus` VALUES (15, 15, '1', '1', '', '2017-6-20 00:05:06', '2017-6-20 00:05:06');
INSERT INTO `users_menus` VALUES (16, 16, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (17, 17, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (18, 18, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (19, 19, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (20, 20, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (21, 21, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (22, 22, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (23, 23, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (24, 24, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (25, 25, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (26, 26, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (27, 27, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (28, 28, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (29, 29, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (30, 30, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (31, 31, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (32, 32, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (33, 33, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (34, 34, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (35, 35, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (36, 36, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (37, 37, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (38, 38, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (39, 39, '1', '1', '', '2017-6-20 00:05:09', '2017-6-20 00:05:09');
INSERT INTO `users_menus` VALUES (40, 40, '1', '1', '', '2019-7-15 12:43:02', '2019-7-15 12:43:02');
INSERT INTO `users_menus` VALUES (41, 41, '1', '1', '', '2019-7-15 12:43:02', '2019-7-15 12:43:02');
INSERT INTO `users_menus` VALUES (42, 42, '1', '1', '', '2019-7-15 12:43:02', '2019-7-15 12:43:02');
INSERT INTO `users_menus` VALUES (43, 43, '1', '1', '', '2019-7-15 12:43:02', '2019-7-15 12:43:02');
INSERT INTO `users_menus` VALUES (44, 44, '1', '1', '', '2019-7-15 12:43:02', '2019-7-15 12:43:02');
INSERT INTO `users_menus` VALUES (45, 45, '1', '1', '', '2020-6-01 12:43:02', '2020-6-01 12:43:02');
INSERT INTO `users_menus` VALUES (46, 46, '1', '1', '', '2020-6-01 12:43:02', '2020-6-01 12:43:02');
INSERT INTO `users_menus` VALUES (47, 47, '1', '1', '', '2020-6-01 12:43:02', '2020-6-01 12:43:02');
INSERT INTO `users_menus` VALUES (48, 48, '1', '1', '', '2020-6-01 12:43:02', '2020-6-01 12:43:02');
INSERT INTO `users_menus` VALUES (49, 49, '1', '1', '', '2020-6-01 12:43:02', '2020-6-01 12:43:02');
INSERT INTO `users_menus` VALUES (50, 50, '1', '1', '', '2020-6-01 12:43:02', '2020-6-01 12:43:02');