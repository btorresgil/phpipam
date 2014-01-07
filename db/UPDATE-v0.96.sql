/* Update from v 0.95 to 0.96 **/
UPDATE `settings` set `version` = '0.96'; 	/* UPDATE version */
/* reset donations */
UPDATE `settings` set `donate` = '0'; 
/* add additional sizes to widgets */
ALTER TABLE `widgets` CHANGE `wsize` `wsize` SET('4','6','8','12')  CHARACTER SET utf8  NOT NULL  DEFAULT '6';
UPDATE `widgets` set `wsize` = '6';
