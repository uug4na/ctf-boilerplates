SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `inv3ntory` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `inv3ntory`;

CREATE TABLE `moneyz` (
  `balance` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `moneyz` (`balance`) VALUES
(5000);

CREATE TABLE `tbl_card_vouchers` (
  `id` int(11) NOT NULL,
  `voucher_no` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `amount` int(4) NOT NULL,
  `is_used` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `tbl_card_vouchers` (`id`, `voucher_no`, `amount`, `is_used`) VALUES
(1, 'Arcane Ring', 100, 0),
(2, 'Duelist Gloves', 100, 0),
(3, 'Lance of Pursuit', 100, 0),
(4, 'Bounty', 100, 0);


ALTER TABLE `tbl_card_vouchers`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tbl_card_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;