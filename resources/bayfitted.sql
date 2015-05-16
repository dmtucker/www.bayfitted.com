-- phpMyAdmin SQL Dump
-- version 4.4.1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: May 16, 2015 at 03:30 AM
-- Server version: 5.5.42
-- PHP Version: 5.6.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `bayfitted`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `index` int(10) unsigned NOT NULL,
  `id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `department` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `type` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `brand` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `number_of_images` tinyint(3) NOT NULL,
  `price` double(8,2) NOT NULL,
  `size` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `detail_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `paypal_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`index`, `id`, `department`, `type`, `brand`, `description`, `number_of_images`, `price`, `size`, `color`, `detail_color`, `paypal_id`) VALUES
(1, '2231', 'accessories', 'hat', 'Bayfitted', 'Blunt Hat', 1, 24.99, 'unknown', 'black,brown', 'na', '9165471'),
(2, '2232', 'accessories', 'hat', 'Bayfitted', 'Bayfitted Hat', 1, 14.99, 'unknown', 'unknown', 'any', '9165491'),
(3, '2233', 'accessories', 'hat', 'Bayfitted', 'SF Bridge Hat', 1, 29.99, 'unknown', 'black', 'na', '9166369'),
(4, '2235', 'accessories', 'hat', 'Bayfitted', 'SF Weed Hat', 1, 19.99, 'unknown', 'unknown', 'any', '9166411'),
(5, '2236', 'accessories', 'hat', 'Bayfitted', 'SF Psychedelic Hat', 2, 14.99, 'unknown', 'unknown', 'na', '9166464'),
(6, '2238', 'accessories', 'hat', 'Bayfitted', 'Go Hard Airbrushed Hat', 1, 24.99, 'unknown', 'unknown', 'black', '9166477'),
(7, '2240', 'accessories', 'hat', 'Bayfitted', 'BM Weed Hat', 2, 29.99, 'unknown', 'black', 'white', '9166485'),
(8, '2243', 'accessories', 'hat', 'Bayfitted', 'BM Rips Hat', 1, 29.99, 'unknown', 'black', 'silver', '9166502'),
(9, '2245', 'accessories', 'hat', 'Bayfitted', 'BM Candy Hat', 1, 29.99, 'unknown', 'white', 'na', '9166511'),
(10, '2248', 'accessories', 'hat', 'Bayfitted', 'BM Love Pills Hat', 1, 24.99, 'unknown', 'white', 'na', '9166516'),
(11, '2291', 'accessories', 'hat', 'Bayfitted', 'SF Christmas Tree Beanie', 1, 19.99, 'unknown', 'unknown', 'any', '9166534'),
(12, '2292', 'accessories', 'hat', 'Bayfitted', 'Reflective SF Skull Beanie', 1, 39.99, 'unknown', 'unknown', 'unknown', '9166530'),
(13, '2198', 'accessories', 'shoes', 'Air Jordan', 'Fire Red Retro Jordan 5', 2, 349.99, 'unknown', 'white', 'red', '9166553'),
(14, '2199', 'accessories', 'shoes', 'Air Jordan', 'Deep Burgundy Retro Jordan 5', 1, 349.99, 'unknown', 'burgundy', 'silver', '9166560'),
(15, '2202', 'accessories', 'shoes', 'Air Jordan', 'Spizike Retro Jordan 5', 2, 449.99, 'unknown', 'black', 'red', '9166569'),
(16, '2204', 'accessories', 'shoes', 'Bayfitted', 'Tiger Stripe Airbrushed Custom', 1, 79.99, 'unknown', 'unknown', 'any', '9166584'),
(17, '2205', 'accessories', 'shoes', 'Bayfitted', 'Bay Airbrushed Custom', 1, 79.99, 'unknown', 'unknown', 'any', '9167400'),
(18, '2206', 'accessories', 'shoes', 'Bayfitted', 'Fresh Airbrushed Custom', 1, 79.99, 'unknown', 'unknown', 'any', '9167419'),
(19, '2207', 'accessories', 'shoes', 'Bayfitted', 'Earth and Sky Airbrushed Custom', 1, 79.99, 'unknown', 'unknown', 'any', '9167469'),
(20, '2208', 'accessories', 'shoes', 'Kashi', 'Kashi', 1, 99.99, 'unknown', 'red', 'gold', '9167492'),
(21, '3211', 'accessories', 'jewelry', 'Bayfitted', 'Superman (Lighter) Chain', 1, 19.99, 'na', 'na', 'na', '9167511'),
(22, '3214', 'accessories', 'jewelry', 'Bayfitted', 'Weed Chain', 1, 19.99, 'na', 'na', 'na', '9167520'),
(23, '3216', 'accessories', 'jewelry', 'Bayfitted', 'Raider (Lighter) Chain', 1, 19.99, 'na', 'na', 'na', '9167524'),
(24, '3217', 'accessories', 'jewelry', 'Bayfitted', 'Jordan Buckle', 1, 19.99, 'na', 'na', 'na', '9167530'),
(25, '2218', 'accessories', 'jewelry', 'Bayfitted', 'Gun Buckle', 2, 19.99, 'na', 'na', 'na', '9167538'),
(26, '2219', 'accessories', 'jewelry', 'Bayfitted', 'Got Beer? (Bottle Opener) Buckle', 2, 19.99, 'na', 'na', 'na', '9167543'),
(27, '2279', 'accessories', 'jewelry', 'Bayfitted', 'Cadillac Buckle', 1, 19.99, 'na', 'na', 'na', '9167548'),
(28, '2281', 'accessories', 'jewelry', 'Bayfitted', 'Hecho en Mexico Buckle', 1, 19.99, 'na', 'na', 'na', '9167551'),
(29, '2284', 'accessories', 'jewelry', 'Bayfitted', 'Money Buckle', 1, 19.99, 'na', 'na', 'na', '9167555'),
(30, '3221', 'accessories', 'other', 'MTN Colors', 'Worldwide Bubble GraffitiLT Limited Edition MTN Can', 1, 21.99, 'na', 'na', 'na', '9999567'),
(31, '3223', 'accessories', 'other', 'MTN Colors', 'Mark Bode Limited Edition MTN Can', 1, 21.99, 'na', 'na', 'na', '9999647'),
(32, '3224', 'accessories', 'other', 'MTN Colors', 'Rime Limited Edition MTN Can', 1, 21.99, 'na', 'na', 'na', '9999707'),
(33, '3226', 'accessories', 'other', 'MTN Colors', 'Vaughn Bode Limited Edition MTN Can', 2, 21.99, 'na', 'na', 'na', '9999752'),
(34, '3228', 'accessories', 'other', 'MTN Colors', 'Sheone Limited Edition MTN Can', 1, 21.99, 'na', 'na', 'na', '9999781'),
(35, '3014', 'men', 'shirt', 'Bayfitted', 'BM Weed Skull T-Shirt', 1, 21.99, 'L', 'black', 'white', '9120980'),
(36, '3028', 'men', 'shirt', 'Bayfitted', 'Nixon T-Shirt', 1, 21.99, 'M', 'black', '', '9121026'),
(37, '3030', 'men', 'shirt', 'Bayfitted', 'Survival T-Shirt', 1, 21.99, 'M', 'white', '', '9121047'),
(38, '3031', 'men', 'shirt', 'Bayfitted', 'Tape T-Shirt', 1, 21.99, 'M,L', 'black', '', '9121073'),
(39, '3036', 'men', 'shirt', 'Bayfitted', 'ASI T-Shirt', 2, 21.99, 'L,XL', 'black', '', '9121094'),
(40, '3040', 'men', 'shirt', 'Bayfitted', 'SF Weed T-Shirt', 5, 21.99, 'any', 'black', '', '9121208'),
(41, '3050', 'men', 'shirt', 'Bayfitted', 'Civ T-Shirt', 2, 21.99, 'M,L,XL', 'any', '', '9121390'),
(42, '3058', 'men', 'shirt', 'Bayfitted', 'Multiple Bulbs T-Shirt', 2, 21.99, 'S', 'black,gray,blue', '', '9121820'),
(43, '3060', 'men', 'shirt', 'Bayfitted', 'Big Bulb T-Shirt', 2, 21.99, 'L,XXL', 'black', '', '9121840'),
(44, '3062', 'men', 'shirt', 'Bayfitted', 'Mask T-Shirt', 2, 21.99, 'M,XXL', 'black', '', '9121928'),
(45, '3161', 'men', 'shirt', 'Bayfitted', 'Always Juicy T-Shirt', 1, 29.99, 'L', 'black', '', '9121952'),
(46, '3162', 'men', 'shirt', 'Bayfitted', 'SF Weed Chain T-Shirt', 1, 29.99, 'any', 'white', '', '9122010'),
(47, '3163', 'men', 'shirt', 'Bayfitted', 'I (spray) NY T-Shirt', 1, 21.99, 'any', 'any', '', '9122081'),
(48, '3164', 'men', 'shirt', 'Bayfitted', 'Eyeball T-Shirt', 1, 21.99, 'any', 'any', '', '9122130'),
(49, '3165', 'men', 'shirt', 'Bayfitted', 'Skull Airbrushed T-Shirt', 2, 34.99, 'any', 'any', '', '9122183'),
(50, '3168', 'men', 'shirt', 'Bayfitted', 'Turntable T-Shirt', 2, 21.99, 'any', 'any', '', '9122225'),
(51, '3170', 'men', 'shirt', 'Bayfitted', 'Invaders T-Shirt', 1, 49.99, 'any', 'any', '', '9122288'),
(52, '3171', 'men', 'shirt', 'Bayfitted', 'Asian Man Airbrushed T-Shirt', 1, 69.99, 'any', 'any', '', '9122351'),
(53, '3179', 'men', 'shirt', 'Bayfitted', 'Hiding Michael T-Shirt', 1, 45.99, 'S,M,L,XL', 'any', '', '9122470'),
(54, '3180', 'men', 'shirt', 'Bayfitted', 'Side Michael T-Shirt', 1, 34.99, 'S,M,L,XL', 'white', '', '9122489'),
(55, '3181', 'men', 'shirt', 'Bayfitted', 'Car Airbrushed T-Shirt', 1, 99.99, 'any', 'white', '', '9122512'),
(56, '3182', 'men', 'shirt', 'Bayfitted', 'Asian Samurai Airbrushed T-Shirt', 1, 119.99, 'any', 'any', '', '9122537'),
(57, '3183', 'men', 'shirt', 'Bayfitted', 'Flowerskull Airbrushed T-Shirt', 1, 99.99, 'any', 'any', '', '9122560'),
(58, '3184', 'men', 'shirt', 'Bayfitted', 'Niner Field Airbrushed T-Shirt', 1, 149.99, 'any', 'any', '', '9122585'),
(59, '3185', 'men', 'shirt', 'Bayfitted', 'Watersnake Airbrushed T-Shirt', 1, 159.99, 'any', 'any', '', '9122625'),
(60, '3186', 'men', 'shirt', 'Bayfitted', '2pac and Malcom X Airbrushed T-Shirt', 1, 179.99, 'any', 'any', '', '9122661'),
(61, '3187', 'men', 'shirt', 'Bayfitted', 'Snakewoman Airbrushed T-Shirt', 1, 179.99, 'any', 'any', '', '9122684'),
(62, '3196', 'men', 'shirt', 'Bayfitted', 'Raiders Airbrushed T-Shirt', 1, 69.99, 'any', 'any', '', '9126923'),
(63, '3197', 'men', 'shirt', 'Bayfitted', 'Niners Airbrushed T-Shirt', 1, 59.99, 'any', 'any', '', '9126935'),
(64, '3264', 'men', 'shirt', 'Bayfitted', 'Skull Tusks T-Shirt', 1, 21.99, 'M', 'black', '', '9126941'),
(65, '3266', 'men', 'shirt', 'Bayfitted', 'US? T-Shirt', 1, 21.99, 'S', 'black', '', '9126952'),
(66, '3268', 'men', 'shirt', 'Bayfitted', 'Blue Walrus T-Shirt', 1, 21.99, 'S', 'black', '', '9126959'),
(67, '3269', 'men', 'shirt', 'Bayfitted', 'SF O.G. T-Shirt', 1, 21.99, 'L', 'red', '', '9126965'),
(68, '3270', 'men', 'shirt', 'Bayfitted', 'Globalization T-Shirt', 1, 21.99, 'XL', 'black', '', '9126976'),
(69, '3271', 'men', 'shirt', 'Bayfitted', '4 of Magazine T-Shirt', 2, 21.99, 'L,XL', 'black', '', '9126980'),
(70, '3273', 'men', 'shirt', 'Bayfitted', 'Cartel T-Shirt', 1, 21.99, 'XL', 'white', '', '9127000'),
(71, '3274', 'men', 'shirt', 'Bayfitted', 'Imagine T-Shirt', 1, 21.99, 'XXL', 'black', '', '9126988'),
(72, '3275', 'men', 'shirt', 'Bayfitted', 'Cutter Lads T-Shirt', 1, 21.99, 'XXXL', 'tan', '', '9127008'),
(73, '3017', 'men', 'jacket', 'Bayfitted', 'Adidas Track Jacket', 1, 49.99, 'XXL', 'teal and red', 'yellow', '9999187'),
(74, '3026', 'men', 'jacket', 'Bayfitted', 'Vein and Muscle Hoodie', 1, 49.99, 'M,XL,XXL', 'white', 'red and black', '9999259'),
(75, '3189', 'men', 'jacket', 'Bayfitted', '49er Helmets Jacket', 1, 89.99, '6XL', 'red', 'black', '9999331'),
(76, '3190', 'men', 'jacket', 'Bayfitted', 'Raider Helmets Jacket', 1, 89.99, 'XL', 'silver', 'black', '9999373'),
(77, '3194', 'men', 'jacket', 'Bayfitted', 'Reversible 49er Jacket', 2, 159.99, 'L,XL,XXL', 'red and gold', 'white', '9999426'),
(78, '3143', 'women', 'shirt', 'Bayfitted', 'Sword in Snake Tank Top', 1, 21.99, 'L', 'black', '', '9144621'),
(79, '3144', 'women', 'shirt', 'Bayfitted', 'Pirate Ship Tank Top', 1, 21.99, 'S,M', 'black', '', '9144716'),
(80, '3145', 'women', 'shirt', 'Bayfitted', 'Silver Skull Tank Top', 1, 21.99, 'M', 'black', '', '9144741'),
(81, '3146', 'women', 'shirt', 'Bayfitted', 'Skull and Crossbones Tank Top', 1, 21.99, 'L', 'black', '', '9144760'),
(82, '3147', 'women', 'shirt', 'Bayfitted', 'Silver Skull T-Shirt', 1, 21.99, 'M,L', 'black', '', '9144776'),
(83, '3148', 'women', 'shirt', 'Bayfitted', 'Mock Argyle Turtleneck', 1, 21.99, 'XL,XXL,XXXL', 'white,pink', '', '9144797'),
(84, '3149', 'women', 'shirt', 'Bayfitted', 'Plaid Button-down', 1, 21.99, 'S,M,L', 'blue,pink', '', '9144815'),
(85, '3150', 'women', 'shirt', 'Bayfitted', 'Collared T-Shirt', 1, 21.99, 'L', 'black', '', '9144836'),
(86, '3151', 'women', 'shirt', 'Bayfitted', 'I (love) TL Tank Top', 2, 19.99, 'L', 'any', '', '9144848'),
(87, '3153', 'women', 'shirt', 'Bayfitted', 'Heli-Globe T-Shirt', 1, 21.99, 'S', 'pink', '', '9144874'),
(88, '3154', 'women', 'shirt', 'Bayfitted', 'SF Hood T-Shirt', 1, 21.99, 'XL', 'black', '', '9144915'),
(89, '3155', 'women', 'shirt', 'Bayfitted', 'SF O.G. T-Shirt', 1, 21.99, 'XL', 'orange', '', '9144936'),
(90, '3157', 'women', 'shirt', 'Bayfitted', 'Got Le Che? T-Shirt', 1, 21.99, 'M,XL', 'white', '', '9144967'),
(91, '3158', 'women', 'shirt', 'Bayfitted', 'Custom Bay Girl Airbrushed T-Shirt', 1, 39.99, 'any', 'any', '', '9145000'),
(92, '3173', 'women', 'jacket', 'Bayfitted', 'Red Berry Houndstooth Jacket', 1, 29.99, 'S', 'gray', 'red', '9145067'),
(93, '3174', 'women', 'jacket', 'Bayfitted', 'Red Berry Plaid Jacket', 1, 29.99, 'S,M', 'white', 'black', '9145092'),
(94, '3175', 'women', 'jacket', 'Bayfitted', 'Bonified Argyle Jacket', 1, 29.99, 'S', 'white', 'blue', '9145116'),
(95, '3176', 'women', 'jacket', 'Bayfitted', 'Peachy Keen Hearts Jacket', 1, 29.99, 'S,M', 'brown', 'pink and green', '9145136'),
(96, '2253', 'kids', 'shirt', 'Bayfitted', 'Wake Up and Live T-Shirt', 1, 19.99, 'L', 'black', '', '9148710'),
(97, '2254', 'kids', 'shirt', 'Bayfitted', 'Jammin Bob T-Shirt', 1, 19.99, '2T', 'black', '', '9148722'),
(98, '2256', 'kids', 'shirt', 'Bayfitted', 'Roots, Rock, Reggae T-Shirt', 1, 19.99, 'M', 'red', '', '9148757'),
(99, '3259', 'kids', 'shirt', 'Bayfitted', 'City T-Shirt', 1, 19.99, 'unknown', 'black', '', '9148785'),
(100, '3260', 'kids', 'shirt', 'Bayfitted', 'Chillin Long Sleeve Shirt', 1, 19.99, 'unknown', 'blue', '', '9148805'),
(101, '3261', 'kids', 'shirt', 'Bayfitted', 'Headphones T-Shirt', 1, 19.99, 'unknown', 'blue', '', '9148816'),
(102, '3249', 'kids', 'onesie', 'Bayfitted', 'Electric Baby Onesie', 1, 19.99, 'L', 'blue', 'black', '9148825'),
(103, '3250', 'kids', 'onesie', 'Bayfitted', 'B is for Bob Onesie', 1, 19.99, 'M', 'blue', 'black', '9148835'),
(104, '3252', 'kids', 'onesie', 'Bayfitted', 'Rasta Baby Onesie', 1, 19.99, 'S', 'yellow', 'green', '9148847'),
(105, '3255', 'kids', 'onesie', 'Bayfitted', 'Nike Package Onesie (set of 3)', 1, 19.99, '0-3 mos', 'pink', 'dark pink', '9148860'),
(106, '3257', 'kids', 'onesie', 'Bayfitted', 'Feed Me! Airbrushed Onesie', 2, 25.99, 'unknown', 'blue', 'black', '9148881'),
(107, '3262', 'kids', 'onesie', 'Bayfitted', 'Feed Me! Onesie', 1, 19.99, 'unknown', 'black', 'blue', '9148889'),
(108, '3263', 'kids', 'onesie', 'Bayfitted', 'Big Bully Onesie', 1, 19.99, 'unknown', 'black', 'pink', '9148897'),
(109, '3429', 'giants', 'shirt', 'Bayfitted', 'Poseyyyyyyyy! T-Shirt', 1, 21.99, 'any', 'black', 'orange', 'AKJE4UMGH6T84'),
(110, '3001', 'giants', 'shirt', 'Bayfitted', 'Texas BBQ T-Shirt', 1, 21.99, 'any', 'white', 'na', 'MKYLRS4RE3LLW');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `index` int(6) NOT NULL,
  `id` int(6) NOT NULL,
  `email` longtext COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `first_name` text COLLATE utf8_unicode_ci NOT NULL,
  `last_name` text COLLATE utf8_unicode_ci NOT NULL,
  `gender` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `last_visit` datetime NOT NULL,
  `last_edit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `date_added` date NOT NULL,
  `greeting` tinyint(1) NOT NULL DEFAULT '1',
  `likes` longtext COLLATE utf8_unicode_ci
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`index`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`index`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `index` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=111;
--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `index` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;