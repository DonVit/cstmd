--comments table
RENAME TABLE  `news_comments` TO  `comment` ;
ALTER TABLE  `comment` CHANGE  `news_id`  `item_id` INT( 11 ) NOT NULL;
ALTER TABLE  `comment` ADD  `item_type` VARCHAR(1) NOT NULL COMMENT  'n - news ,p - photo' AFTER  `id` ;
update `comment` set `item_type`='n';
insert into `comment`(`item_type`,`item_id`,`name`,`email`,`web`,`comment`,`date`,`deleted` )SELECT 'p',`photos_id`,`name`,`email`,`web`,`comment`,`date`,`deleted` FROM `photos_comments` ;
DROP TABLE  `photos_comments`;


--photos table
ALTER TABLE  `photos`
ADD  `note` VARCHAR( 1000 ) NOT NULL AFTER  `title` , 
ADD  `raion_id` INT NOT NULL AFTER  `file` ,
ADD  `localitate_id` INT NOT NULL AFTER  `raion_id` ,
ADD  `centerlat` FLOAT( 20, 16 ) NOT NULL AFTER  `localitate_id` ,
ADD  `centerlng` FLOAT( 20, 16 ) NOT NULL AFTER  `centerlat` ,
ADD  `lat` FLOAT( 20, 16 ) NOT NULL AFTER  `centerlng` ,
ADD  `lng` FLOAT( 20, 16 ) NOT NULL AFTER  `lat` ,
ADD  `zoom` TINYINT NOT NULL AFTER  `lng` ,
ADD  `maptype` TINYINT NOT NULL AFTER  `zoom`,
ADD  `deleted` INT NOT NULL AFTER  `contor` ;


--company_type table
RENAME TABLE  `news_source_type` TO  `company_type` ;
ALTER TABLE  `company_type` ADD  `deleted` INT NOT NULL AFTER  `modified_date` ;

--company table
RENAME TABLE  `news_source` TO  `company` ;
ALTER TABLE  `company` CHANGE  `news_source_type_id`  `company_type_id` INT( 11 ) NOT NULL;
ALTER TABLE  `company`
ADD  `raion_id` INT NOT NULL AFTER  `logo_description` ,
ADD  `localitate_id` INT NOT NULL AFTER  `raion_id` ,
ADD  `sector_id` INT NOT NULL AFTER  `localitate_id`,
ADD  `centerlat` FLOAT( 20, 16 ) NOT NULL AFTER  `email` ,
ADD  `centerlng` FLOAT( 20, 16 ) NOT NULL AFTER  `centerlat` ,
ADD  `lat` FLOAT( 20, 16 ) NOT NULL AFTER  `centerlng` ,
ADD  `lng` FLOAT( 20, 16 ) NOT NULL AFTER  `lat` ,
ADD  `zoom` TINYINT NOT NULL AFTER  `lng` ,
ADD  `maptype` TINYINT NOT NULL AFTER  `zoom`,
ADD  `deleted` INT NOT NULL AFTER  `modified_date` ;

-- news table
ALTER TABLE  `news` CHANGE  `news_source`  `company_id` INT( 11 ) NOT NULL;
ALTER TABLE  `news` CHANGE  `map_centerlat`  `centerlat` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE  `map_centerlng`  `centerlng` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE  `map_zoom`  `zoom` TINYINT( 4 ) NOT NULL ,
CHANGE  `map_maptype`  `maptype` TINYINT( 4 ) NOT NULL ,
CHANGE  `map_lat`  `lat` VARCHAR( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE  `map_lng`  `lng` VARCHAR( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL

-- imobil table
ALTER TABLE  `imobil` ADD  `templat` FLOAT( 20, 16 ) NOT NULL AFTER  `deleted` ;
update `imobil` set templat=lat;
update `imobil` set lat=lng;
update `imobil` set lng=templat;
ALTER TABLE  `imobil` DROP  `templat`;

-- logs table

CREATE TABLE  `logs` (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `sql` VARCHAR( 5000 ) NOT NULL ,
 `status` TINYINT NOT NULL ,
 `datetime` DATETIME NOT NULL
) ENGINE = MYISAM ;

--feedback

CREATE TABLE  `feedback` (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `user_id` INT NOT NULL ,
 `name` VARCHAR( 100 ) NOT NULL ,
 `email` VARCHAR( 100 ) NOT NULL ,
 `text` VARCHAR( 1000 ) NOT NULL ,
 `datetime` DATETIME NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE  `feedback` ADD  `deleted` INT NOT NULL AFTER  `datetime` ;


--imobil

update `imobil` set deleted=1 WHERE status in (1,2)




--migrarare datelor in utf8_general_ci

--contact table

ALTER TABLE  `contact` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

ALTER TABLE  `contact` CHANGE  `name`  `name` VARCHAR( 50 ) NOT NULL ,
CHANGE  `url`  `url` VARCHAR( 500 ) NULL DEFAULT NULL ,
CHANGE  `phone`  `phone` VARCHAR( 50 ) NULL DEFAULT NULL ,
CHANGE  `mobile`  `mobile` VARCHAR( 50 ) NULL DEFAULT NULL ,
CHANGE  `email`  `email` VARCHAR( 50 ) NOT NULL ,
CHANGE  `note`  `note` VARCHAR( 500 ) NULL DEFAULT NULL;	

--user table

ALTER TABLE  `user` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

ALTER TABLE  `user` CHANGE  `name`  `name` VARCHAR( 50 ) NOT NULL ,
CHANGE  `url`  `url` VARCHAR( 500 ) NULL DEFAULT NULL ,
CHANGE  `phone`  `phone` VARCHAR( 50 ) NULL DEFAULT NULL ,
CHANGE  `mobile`  `mobile` VARCHAR( 50 ) NULL DEFAULT NULL ,
CHANGE  `email`  `email` VARCHAR( 50 ) NOT NULL ,
CHANGE  `password`  `password` VARCHAR( 50 ) NOT NULL ,
CHANGE  `note`  `note` VARCHAR( 500 ) NULL DEFAULT NULL;


--imobil table

ALTER TABLE  `imobil` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

ALTER TABLE  `imobil` CHANGE  `bonitate`  `bonitate` VARCHAR( 5 ) NULL DEFAULT NULL ,
CHANGE  `strada`  `strada` VARCHAR( 250 ) NULL DEFAULT NULL ,
CHANGE  `casa_nr`  `casa_nr` VARCHAR( 10 ) NULL DEFAULT NULL ,
CHANGE  `scara_nr`  `scara_nr` VARCHAR( 50 ) NULL DEFAULT NULL ,
CHANGE  `apartament_nr`  `apartament_nr` VARCHAR( 10 ) NULL DEFAULT NULL ,
CHANGE  `noteadresa`  `noteadresa` VARCHAR( 500 ) NULL DEFAULT NULL ,
CHANGE  `note`  `note` VARCHAR( 500 ) NULL DEFAULT NULL;

--maps table

CREATE TABLE  `maps` (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `title` VARCHAR( 100 ) NOT NULL ,
 `description` VARCHAR( 1000 ) NOT NULL ,
 `lat` FLOAT( 20, 16 ) NOT NULL ,
 `lng` FLOAT( 20, 16 ) NOT NULL ,
 `centerlat` FLOAT( 20, 16 ) NOT NULL ,
 `centerlng` FLOAT( 20, 16 ) NOT NULL ,
 `zoom` TINYINT NOT NULL ,
 `maptype` TINYINT NOT NULL ,
 `deleted` TINYINT NOT NULL
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;

ALTER TABLE  `maps` ADD  `contor` INT NOT NULL AFTER  `maptype` ;

-- ads

CREATE TABLE `ads_message` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(200) NOT NULL,
  `text` varchar(2000) NOT NULL,
  `datetime` datetime NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE `ads_user` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(200) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE `ads_messageuser` (
  `id` int(11) NOT NULL auto_increment,
  `ads_message_id` int(11) NOT NULL,
  `ads_user_id` int(11) NOT NULL,
  `key` varchar(100) collate latin1_general_ci NOT NULL,
  `sent` tinyint(4) NOT NULL default '0',
  `senddate` datetime NOT NULL,
  `visited` tinyint(4) NOT NULL,
  `visitdate` datetime NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

insert into ads_user(email) SELECT email FROM  `contact` WHERE email like '%@%' and email not in (select email from ads_user) group by email 
insert into ads_user(email) SELECT email FROM  `company` WHERE company.valid=1 and email like '%@%' and email not in (select email from ads_user) group by email 
insert into ads_user(email) SELECT email FROM  `user` WHERE email like '%@%' and email not in (select email from ads_user) group by email 


insert into ads_messageuser(ads_message_id,ads_user_id) SELECT 1 , id FROM `ads_user`



ALTER TABLE  `ads_user` ADD  `source` TINYINT NOT NULL DEFAULT  '0' COMMENT  '1 - site, 0 - other' AFTER  `email` ;
update `ads_user` set source=1;
CREATE TABLE  `tmp` (
 `email` VARCHAR( 200 ) NOT NULL
) ENGINE = MYISAM ;
insert into `tmp`(email) values ("danandimobil@mail.ru"), ("succes-imobil@mail.md"), ("masic.79@mail.ru"), ("sashatudor@gmail.com"), ("jet_li_83@rambler.ru"), ("sudvest@gmail.com"), ("action-50@mail.ru"), ("Siroja777@mail.ru"), ("ghenadie1980@mail.ru"), ("anusca1983@yahoo.com"), ("57112-37@mail.ru"), ("andrei-m89@mail.ru"), ("batir@mail.md"), ("adronic28@rambler.ru"), ("sergheisdsh@mail.ru"), ("www.gazda.md@mail.md"), ("pldi88@mail.ru"), ("Skiffik666@mail.ru"), ("evropa-7@ukr.net"), ("vvv-niyk@mail.ru"), ("welcome.md@mail.ru"), ("cmoraru@mail.md"), ("irina_Arno@mail.ru"), ("leoiulia@mail.ru"), ("svictoriea7@rambler.ru"), ("gheceanu@mail.ru"), ("djek-barber@mail.md"), ("LAANA-@MAIL.RU"), ("freesia-srl@yandex.ru"), ("garstand@yahoo.com"), ("margo_love06@mail.ru"), ("azebra.has@gmail.com"), ("simbotianu@rambler.ru"), ("seminash80@mail.ru"), ("infocart@mail.ru"), ("Calinciuc_alex@mail.ru"), ("Victor_Mers@mail.ru"), ("kst27@mail.ru"), ("life_border@mail.ru"), ("anya-prodan@mail.ru"), ("calendrina@rambler.ru"), ("dm_nedelcev@rambler.ru"), ("viorel26@yandex.ru"), ("golubi.net@mail.ru"), ("smirror@rambler.ru"), ("rec-srl@rambler.ru"), ("aurelia.71@mail.ru"), ("lpatrasco@gmail.com"), ("Chicaros_anna@mail.ru"), ("alidem@bk.ru"), ("natalya_balin@mail.ru"), ("lupuanatol@rambler.ru"), ("kow777@mail.ru"), ("dsa70@mail.ru"), ("fris@bk.ru"), ("guitar-method@narod.ru"), ("apartamente1@yandex.ru"), ("triticuscom@mail.ru"), ("aquaflower@rambler.ru"), ("DAV2007@LAND.RU"), ("cva0211@rambler.ru"), ("koktelikavi@mail.ru"), ("am.oleg@mail.ru"), ("sync2008@inbox.ru"), ("flpemd@gmail.com"), ("badea_misa@mail.ru"), ("scleppi@mail.ru"), ("bigmadi@mail.ru"), ("vioghelbet@mail.ru"), ("nicodem52@rambler.ru"), ("ketrari@list.ru"), ("fs.73.73@mail.ru"), ("triticus_com@mail.ru"), ("liusy2004@mail.ru"), ("webdesfreelance@gmail.com"), ("777aliona_rom@list.ru"), ("david06@inbox.ru"), ("iurconsuljur@gmail.com"), ("marina.cirimpei@gmail.com"), ("an-ton2009@mail.ru"), ("protvchis@km.ru"), ("fain@mail.md"), ("valicfediuc@mail.ru"), ("diferitlux@gmail.com"), ("grandwork@mail.ru"), ("alexsved@mail.ru"), ("fokshapg@rambler.ru"), ("liomela@mail.ru"), ("mironval@mail.ru"), ("mdmisa@mail.md"), ("apartamente@eurohouse.md"), ("luminita.rosca@mail.ru"), ("stlucia@mail.ru"), ("miroshnik33@list.ru"), ("nicolae_baimastruc@yahoo.com"), ("adrian.lazu@gmail.com"), ("valy2006neo@rambler.ru"), ("fsb.com@fsb.com.md"), ("euroflats@mail.ru"), ("com029@mail.ru"), ("jamet@mail.ru"), ("botocdiana@mail.ru"), ("altranscon-grup@mail.com"), ("dav2007@land.r"), ("rulant@rambler.ru"), ("lumina2006@yandex.ru"), ("fruandy@yahoo.com"), ("manadm@mail.ru"), ("scacush@mail.ru"), ("ivb1986@rambler.ru"), ("liru2000@gmail.com"), ("murcik61@mail.ru"), ("vivans@mail.ru"), ("boy-76@mail.ru"), ("autodoroga@gmail.com"), ("39svetkov@mail.ru"), ("alexandruistratii@yahoo.com"), ("bogdan.gabriela1@gmail.com"), ("teatea01@mail.ru"), ("dianastavila@yahoo.com"), ("trotiuc@mail.ru"), ("sergiu_motroi@mail.ru"), ("Aleksyy@mail.ru"), ("stevenalex@yandex.ru"), ("mihai19a@rambler.ru"), ("serghey.gherman@gmail.com"), ("vioreles@yahoo.es"), ("igz88@mail.ru"), ("SLAVIC.84@MAIL.RU"), ("bezholeg@yandex.ru"), ("conlitmash@rambler.ru"), ("lkpark@mail.ru"), ("keysing@mail.ru"), ("riabchun@mail.ru"), ("iuriecojokaru@rambler.ru"), ("maslinka@inbox.ru"), ("navat-tir@mail.ru"), ("fasadnik@bk.ru"), ("daranuta@mail.ru"), ("viccons@mail.ru"), ("28stan@mail.ru"), ("dprorom@yahoo.com"), ("stopcinschi@gmail.com"), ("info@alpinasud.md"), ("buchkov22@mail.ru"), ("iren200680@mail.ru"), ("veteran@arax.m"), ("a.a_t@mail.ru"), ("contescu123@mail.md"), ("malairaus@yahoo.com"), ("trtmarcela@yahoo.ie"), ("alex_ceban@mail.ru"), ("marketimobile@gmail.com"), ("apartment_md@mail.ru"), ("aurelbostan@yandex.ru"), ("vvrabie@mail.ru"), ("orlando.simba@mail.md"), ("tosea01@mail.ru"), ("mironcon@yahoo.com"), ("nanu66@mail.ru"), ("www.imobilemd.com@mail.md"), ("gheorghe.meleca@gmail.com"), ("c-alexei@mail.ru"), ("K-LINA-3@yandex.ru"), ("gmayk@mail.ru"), ("euroservices@mail.ru"), ("ursm80@mail.ru"), ("remonal@mail15.com"), ("verondis@mail.ru"), ("info@nordik.md"), ("silivica_a@yahoo.com"), ("eurohouse08@mail.ru"), ("dasutka@list.ru"), ("alemega@yandex.ru"), ("mehryban@rambler.ru"), ("vladimirmd67@mail.ru"), ("sveta.girman@gmail.com"), ("andrei777@gmail.com"), ("alexm@moldpent.com"), ("Mihu_Atemistu@Yahoo.com"), ("regatimobil@mail.ru"), ("cspinei@md.soluziona.com"), ("framuga@km.ru"), ("serghei_80@mail.ru"), ("covaliji_e@mail.ru"), ("abocancea@yandex.ru"), ("prikolew@mail.ru"), ("codreanu_vitalie@yahoo.com"), ("inger79@mail.ru"), ("ruslik000@rambler.ru"), ("wara-8@mail.ru"), ("serghey.e@gmail.com"), ("laura_ki@mail.ru"), ("prosperitas@mail.md"), ("maxart_md@inbox.ru"), ("baldessarini84@yahoo.com"), ("lean7@yandex.ru"), ("System90@rambler.ru"), ("savcenco-elena@rambler.ru"), ("kaktus87@inbox.ru"), ("glebovalexr@yahoo.com"), ("antip59@mail.ru"), ("cristi@hotmail.ru"), ("pitesti@mail.ru"), ("Greekmdl@gmail.com"), ("mail-ost-pacu@mail.ru"), ("turcan2007@yahoo.com"), ("apciagi@rambler.ru"), ("ROSCA@IPB.MD"), ("Sicvamd@mail.md"), ("info@marriott.md"), ("sergione59@mail.md"), ("nadia59@inbox.ru"), ("aliona_vasile@yahoo.com"), ("apartments-rent@mail.ru"), ("natka012@mail.ru"), ("vik-71@inbox.ru"), ("delfinela@gmail.com"), ("anrot2006@rambler.ru"), ("nskgroup@mail.ru"), ("vits7747@pochta.ru"), ("chudina_neli@mail.ru"), ("ab-fashion@mail.ru"), ("sherbanna@yandex.ru"), ("nvm.imobil@gmail.com"), ("imobilar_srl@yahoo.com"), ("expedit72@mail.ru"), ("prozorovski-kris@mail.ru"), ("amaya@rambler.ru"), ("sergiu-c@yandex.ru"), ("gorod0@mail.ru"), ("tatpancenco@rambler.ru"), ("andi@kirsan.md"), ("elisera@mail.ru"), ("demodesign@mail.md"), ("mindrescu@rambler.ru"), ("zmeuilie@yahoo.com"), ("popov_alexandr@list.ru"), ("maxkoch11@mail.ru"), ("schveik58@mail.ru"), ("info@velimobil.md"), ("durnescu@rambler.ru"), ("cmaria@mail.md"), ("stagzb@yahoo.com"), ("victoriahouse@mail.ru"), ("fusu.katerina@gmail.com"), ("info@cimprim.md"), ("george0305@yandex.ru"), ("info@premier-construct.md"), ("zmuncila@rambler.ru"), ("sahco07@mail.ru"), ("valter_v65@mail.ru"), ("consulting_imobil@mail.md"), ("ttodika@gmail.com"), ("rjevschiala@yahoo.com"), ("agricirodica@mail.ru"), ("cocncv@rambler.ru"), ("tanyai1@yandex.ru"), ("promstroi-grup@msn.com"), ("irina_corcimari@yahoo.com"), ("vip_imobil@mail.ru"), ("vdragancea@yandex.ru"), ("ar.electro@mail.ru"), ("antoninaantonina@rambler.ru"), ("rentmoldova@gmail.com"), ("lop.59@mail.ru"), ("stela@impexservice.com"), ("emishin@mail.ru"), ("polimarutany@yahoo.com"), ("milatretiac@mail.ru"), ("carolinamanoli@yahoo.com"), ("7-8-9@mail.ru"), ("55gm55@mail.ru"), ("1968mars@rambler.ru"), ("akidor79@mail.ru"), ("radcor9999@yahoo.com"), ("lora_melint@mail.ru"), ("EGRIGO@Rambler.ru"), ("lgrigoriu@gmail.com"), ("imad2004@rambler.ru"), ("consultgrup@list.ru"), ("gooddddddd@mail.ru"), ("GOD4YOU@MAIL.RU"), ("burcas@list.ru"), ("rimih@mail.md"), ("usmcez@rambler.ru"), ("bazylshmd@yahoo.com"), ("consults@mail.md"), ("vaidas2007@yandex.ru"), ("2an-@mail.ru"), ("delfos@mail.ru"), ("mazi_andrei@yahoo.com"), ("olexia11@yahoo.com"), ("vladislavs@mail.md"), ("antoniuchelsea@mail.ru"), ("aticaplus@mail.ru"), ("cocutza@mail.ru"), ("basilshmd@yahoo.fr"), ("arnaut1969@bk.ru"), ("ja_crew@bk.ru"), ("ctvaler@yahoo.com"), ("f.c.mondial@mail.ru"), ("sab_ianny@yahoo.com"), ("stnik@yandex.ru"), ("annadiana_76@mail.ru"), ("euroimobil.grup@gmail.com"), ("s_gutu@mail.ru"), ("tarol@gmx.net"), ("ionradetchi@mail.md"), ("abotnari@list.ru"), ("grkush@mail.ru"), ("PERPELEA@MAIL.RU"), ("estate@immuno.md"), ("burencova@yahoo.com"), ("son-84@mail.ru"), ("korall1985@mail.ru"), ("aurel_69@mail.ru"), ("vipero60@mail.ru"), ("auras0916@yahoo.com"), ("vicodreanu@gmail.com"), ("jorik1914@yahoo.com"), ("istra_realty@yahoo.com"), ("sergiu5costin@mail.md"), ("aurelviorica@mail.ru"), ("kapital@pochtamt.ru"), ("isvirad@mail.ru"), ("trofanita@mail.ru"), ("info-colamina@mail.ru"), ("talex1998@mail.ru"), ("vikipahom@yahoo.com"), ("savon@mail.md"), ("anatolii-popusoi@rambler.ru"), ("taniusha_x@mail.ru"), ("vsmicec@mail.ru"), ("gazda.md@mail.ru"), ("vdragancea@mail.ru"), ("angela.cojocari@yahoo.it"), ("e_miliian@mail.ru"), ("nidan75@mail.ru"), ("cozari_o@mail.ru"), ("sagemd@rambler.ru"), ("capelica@yandex.ru"), ("dimolla@mail.ru"), ("danielli@mail.md"), ("anatolie.soltinskii@rambler.ru"), ("andrei.lazu@rambler.ru"), ("dci_imobil@yahoo.com"), ("lisnic_adrian@mail.ru"), ("VILEA_@MAIL.RU"), ("vovceek@mail.ru"), ("m_grig@mail.md"), ("monsoon12@mail.ru");


// name_ro,lat,lng for location

ALTER TABLE  `localitate` ADD  `name_ro` VARCHAR( 255 ) NOT NULL AFTER  `name` ,
ADD  `lat` FLOAT( 20, 16 ) NOT NULL AFTER  `name_ro` ,
ADD  `lng` FLOAT( 20, 16 ) NOT NULL AFTER  `lat` ;

import loctmp.sql

update localitate as l, loctmp as l1 set l.name_ro=l1.name, l.lat=l1.lat, l.lng=l1.lng where l.id=l1.id

// add video

CREATE TABLE `video` (
  `id` int(11) NOT NULL auto_increment,
  `company_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `text` varchar(1000) NOT NULL,
  `description` varchar(200) NOT NULL,
  `keywords` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `datetime` datetime NOT NULL,
  `deleted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE `news_video` (
  `id` int(11) NOT NULL auto_increment,
  `news_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;


// add judet

ALTER TABLE  `raion` ADD  `judet_id` INT NOT NULL AFTER  `id` ;


//
select l.id, l.name, l.lat, t.id, t.v from localitate as l, (SELECT * FROM `node_tags` WHERE k='name') as t where lat=0 and name=v
