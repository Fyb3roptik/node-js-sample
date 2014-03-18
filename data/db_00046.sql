DELETE FROM `config` WHERE `config_key` IN ('HOME_PAGE', 'GLOBAL_FOOTER');

INSERT INTO `config` (`config_key`, `config_value`, `config_text`) VALUES
('GLOBAL_FOOTER', '', '&copy; 2012 siing.com | All Rights Reserved <br />');