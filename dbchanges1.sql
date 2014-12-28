copy tables:
company
news 
contact
image

ALTER TABLE  `company` ADD  `casa_nr` VARCHAR( 50 ) NOT NULL AFTER  `strada` ,
ADD  `scara_nr` VARCHAR( 50 ) NOT NULL AFTER  `casa_nr` ,
ADD  `apartament_nr` VARCHAR( 50 ) NOT NULL AFTER  `scara_nr` ,
ADD  `noteadresa` VARCHAR( 500 ) NOT NULL AFTER  `apartament_nr` ;


ALTER TABLE  `contact` ADD  `skypeid` VARCHAR( 100 ) NOT NULL AFTER  `email` ,
ADD  `fax` VARCHAR( 100 ) NOT NULL AFTER  `skypeid` ;

ALTER TABLE  `contact` CHANGE  `name`  `contactname` VARCHAR( 50 )  NOT NULL ,
CHANGE  `note`  `notecontact` VARCHAR( 500 )  NULL DEFAULT NULL;

ALTER TABLE  `contact` CHANGE  `url`  `contacturl` VARCHAR( 500 ) NULL DEFAULT NULL;

ALTER TABLE  `company` CHANGE  `phone1`  `phone` VARCHAR( 100 )  NOT NULL;

ALTER TABLE  `company` ADD  `skypeid` VARCHAR( 100 ) NOT NULL AFTER  `email` ,
ADD  `notecontact` VARCHAR( 500 ) NOT NULL AFTER  `skypeid` ;

ALTER TABLE  `company` CHANGE  `website`  `url` VARCHAR( 100 )  NULL DEFAULT NULL;

ALTER TABLE  `company` CHANGE  `url`  `contacturl` VARCHAR( 100 ) NULL DEFAULT NULL;

ALTER TABLE  `image` CHANGE  `imobil_id`  `reftypeid` INT( 11 ) NOT NULL;
ALTER TABLE  `image` ADD  `reftype` VARCHAR( 1 ) NOT NULL AFTER  `id`;
ALTER TABLE  `image` ADD  `imageurl` VARCHAR( 1000 ) NOT NULL AFTER  `reftypeid`;

ALTER TABLE  `image` CHANGE  `filename`  `imagename` VARCHAR( 250 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE  `filepath`  `imagepath` VARCHAR( 250 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE  `note`  `imagenote` VARCHAR( 250 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
CHANGE  `main`  `imagemain` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  'Main image,1- yes, 0 - no ';

ALTER TABLE `company`
  DROP `logo_file`,
  DROP `logo_description`;
  
ALTER TABLE `news`
  DROP `image_file`,
  DROP `image_url`,
  DROP `image_description`;
  
  
update image inner join imobil on image.reftypeid=imobil.id set reftype='i' where imobil.scop_id in (1,3);
update image inner join imobil on image.reftypeid=imobil.id set reftype='c' where imobil.scop_id in (2,4);

insert into image (`reftype`, `reftypeid`, `imageurl`, `imagename`, `imagepath`, `imagenote`, `imagemain`)
SELECT 'f',id, '','',logo_file, logo_description,1 FROM  `company_1` WHERE logo_file !=  '';

insert into image (`reftype`, `reftypeid`, `imageurl`, `imagename`, `imagepath`, `imagenote`, `imagemain`)
SELECT 'n',id, '',`image_url`, `image_file`,  `image_description`,1 FROM  `news_1` WHERE image_file!=  '';