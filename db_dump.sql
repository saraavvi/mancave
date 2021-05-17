-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 16, 2021 at 01:34 PM
-- Server version: 5.7.32
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `mancaveshop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`) VALUES
(1, 'LEGO'),
(4, 'Zoffoli'),
(5, 'Millwood Pines'),
(6, 'Barewalls');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(250) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Hobbies'),
(2, 'Books'),
(3, 'Interior Decoration'),
(4, 'Health & Beauty');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `address` varchar(150) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_id` int(11) NOT NULL,
  `shipped_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_contents`
--

CREATE TABLE `order_contents` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `price_each` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8_swedish_ci NOT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `description` varchar(10000) COLLATE utf8_swedish_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `stock` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `image` varchar(250) COLLATE utf8_swedish_ci NOT NULL,
  `specification` varchar(10000) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `category_id`, `brand_id`, `stock`, `image`, `specification`) VALUES
(1, 'Colosseum', 549, 'Nowhere on Earth compares to the majesty of the Colosseum of Rome. So, get ready to escape your everyday life as you take on the biggest ever LEGO® build (as at November 2020) yet. This epic 9,036-piece Colosseum model depicts each part of the famous structure in great detail. Authentic detailing shows the northern part of the outer wall’s facade and its iconic arches. The model features 3 stories, adorned with columns of the Doric, Ionic and Corinthian orders while the attic is decorated with Corinthian pilasters.', 1, 1, 20, 'https://www.pricerunner.se/product/640x640/3000782206/Lego-Creator-Colosseum-10276.jpg', 'Item: 10276,\r\n      Pieces: 9036,\r\n      Dimensions:\r\n      H: 11&quot; (27cm)\r\n      W: 21&quot; (52cm)\r\n      D: 24&quot; (59cm)'),
(2, 'NASA Space Shuttle Discovery', 199, 'Celebrate the wonders of space with this LEGO® NASA Space Shuttle Discovery (10283) model building set for adults. With 2,354 pieces, this engaging challenge lets you build the Space Shuttle Discovery, plus the Hubble Space Telescope, launched on NASA’s STS-31 mission in 1990.', 1, 1, 20, 'https://www.pricerunner.se/product/640x640/3001653943/Lego-NASA-Space-Shuttle-Discovery-10283.jpg', 'Item: 10283,\r\n      Pieces: 2354,\r\n      Dimensions:\r\n      H: 9&quot; (21cm)\r\n      W: 14&quot; (34cm)\r\n      D: 22&quot; (54cm)'),
(3, 'Old Trafford - Manchester United', 299, 'Manchester United fans will love building this big LEGO® set model of Old Trafford (10272) to show their allegiance to one of the world’s most famous football clubs. The building set for this LEGO football stadium provides a fun challenge to create a spectacular showpiece model.\r\n\r\n      The ultimate Manchester United gift\r\n      Manchester United fans can show off this Old Trafford replica at home or the office. New for February 2020, this LEGO® Creator sports set coincides with the stadium’s 110th anniversary. This building kit for adults features several evocative details including the players’ tunnel and the statue of the United Trinity.', 1, 1, 20, 'https://www.pricerunner.se/product/640x640/3000133656/Lego-Creator-Old-Trafford-Manchester-United-10272.jpg', 'Item: 10272,\r\n      Pieces: 3898,\r\n      Dimensions:\r\n      H: 8&quot; (19cm)\r\n      W: 16&quot; (39cm)\r\n      D: 19&quot; (47cm)'),
(4, 'Ghostbusters™ ECTO-1', 199, 'If you’re a Ghostbusters™ fan, we’ve got just the thing for you – the LEGO® Ghostbusters ECTO-1! Bust the stress out of everyday life and indulge in some quality me time as you build a LEGO version of the converted 1959 Cadillac Miller-Meteor ambulance from the Ghostbusters movies.\r\n\r\n      Spookily good details\r\n      Based on the Ghostbusters: Afterlife movie version, this ECTO-1 model features working steering, a trapdoor, ghost trap, an extending rear gunner seat, proton pack and cool details from the original car such as the iconic Ghostbusters logo.', 1, 1, 20, 'https://www.pricerunner.se/product/640x640/3000783873/Lego-Creator-Ghostbusters-ECTO-1-10274.jpg', 'Item: 10274,\r\n      Pieces: 2352,\r\n      Dimensions:\r\n      H: 10&quot; (23cm)\r\n      W: 7&quot; (17cm)\r\n      D: 19&quot; (47cm)'),
(5, 'Giunone Laguna', 2895, 'Our most popular bar globe can store up to 9 glasses and 2 or 3 bottles inside its antique 16th Century southern sphere. Suitable for both large and small spaces, this finely detailed bar globe will add a classic twist to your home. Chestnut-stained solid hardwood legs and a beautifully illustrated lower-base shelf compose the stand. The globe sphere fully rotates around its meridian either when it is open or closed. You can easily move it thanks to its casters.\r\n\r\n- Designed to give a touch of elegance and originality to your living room or study\r\n\r\n- Ideal to accompany an aperitif or an after dinner with friends or work colleagues\r\n\r\n- Perfect for decorating rooms in a classic style but does not exclude more modern locations\r\n\r\n- Suitable for those with small spaces (for greater enhancement of the product we suggest placing it in a space of at least 70 x 70 cm)\r\n\r\nBar accessories not included.', 3, 4, 3, 'https://nimax-img.de/Produktbilder/big/6673_1/Zoffoli-Globe-Bar-Giunone-Laguna-40cm.jpg', '- Total dimensions: 50 × 50 × h 93 cm\r\n\r\n- Sphere diameter: 40 cm, the inner compartment can accommodate up to 9 glasses and 2 or 3 bottles. Additional bottles can be stored on the lower base.\r\n\r\n- 16th century map\r\n\r\n- Colour: Laguna\r\n\r\n- Structure: 3 legs made of beech wood, northern meridian, internal rotating bottle rack, wheels.'),
(6, 'Giasone', 4095, 'Trolley globe with drink cabinet and tray\r\n\r\nEntertain your friends with this convenient bar globe which has been designed with hospitality and social life concept in mind! The upper serving tray in addition to the spacious drinks cabinet inside the globe itself, it will provide an excellent space to store bar accessories, decanters, glassware and your favourite bottles of wine and liquor. With its solid base, decorated by hand-finished screen-printings, this globe is a fine addition to any room. The globe sphere, whose map is a faithful reproduction of a 16th Century cartography, fully rotates around its meridian either when it is open or closed.\r\n\r\n- Designed to give a touch of elegance to your living room\r\n\r\n- Ideal to accompany an aperitif or an after dinner with friends or work colleagues\r\n\r\n- Perfect for decorating rooms in a classic style but does not exclude more modern locations\r\n\r\n- Suitable for those with large spaces (for greater enhancement of the product we suggest placing it in an environment of at least 90 x 70cm)\r\n\r\nBar accessories are not included.', 3, 4, 7, 'https://www.zoffoli.com/350-large_default/90.jpg', '- Dimensions: 72 × 53 × h 93 cm\r\n\r\n- Sphere diameter: 40 cm, the inner compartment can accommodate up to 9 glasses and 2/3 bottles. Additional bottles can be stored in the lower compartment and in the side tray.\r\n\r\n- 16th century map\r\n\r\n- Color: classic'),
(7, 'Vivalto', 4095, 'Vintage trolley bar globe on antiqued finish stand and classic ivory map. With its wonderful illustrations of sea monsters, mermaids and mythological figures, Vivalto globe can carry 2 or 3 bottles and approximately 9 glasses. The upper serving tray will provide an excellent space to store bar accessories, decanters, glassware and your favorite bottles of wine and liquor while additional bottles can be placed on the unique lower base shelf.\r\n\r\nIn this vintage styled map, imagination comes to life… A fascinating journey begins, one that combines the charm for maps, mythological illustrations and ancient symbolism. The planispheres realised with our Vintage Map will accompany you in this wonderful journey to the discovery of unexplored territories, with ancient sea routes, wind roses, sea monsters and mermaids. Reality and fantasy come together to create an horizon full of suggestions. A journey between space and time reserved for modern Argonauts thirsting for adventures.\r\n\r\n- Designed to give a touch of elegance to your living room\r\n\r\n- Ideal to accompany an aperitif or an after dinner with friends or work colleagues\r\n\r\nBar accessories not included.', 3, 4, 10, 'https://www.bronze-sculpture-art.com/wp-content/uploads/sites/2/2020/09/podlogowy-elegancki-bar-globe-z-taca.jpg', '- Dimensions: 80 × 50 × h 95 cm\r\n\r\n- Sphere diameter: 42 cm, the inner compartment can accommodate up to 9 glasses and 2/3 bottles. Additional bottles can be stored in the lower compartment and in the side tray.\r\n\r\n- Map: vintage\r\n\r\n- Colour: Ivory'),
(8, 'Minerva Blue Ocean', 3000, 'Elegant bar globe with wheels “Minerva Blue Ocean”\r\n\r\nIf you are looking for an original and quality piece of furniture, Minerva is the ideal choice. This bar globe is perfect for storing your favorite liqueurs to serve to your guests on special occasions. Wherever you choose to position it will certainly not go unnoticed!\r\n\r\nElegant floor-standing bar globe, Minerva has an inner compartment that can hold up to 9 glasses and 2 or 3 bottles. Additional bottles can be stored on the lower wooden base, beautifully decorated with screen-printing natural water-based colors. The map is an accurate reproduction of a 16th Century cartography. You can easily move it thanks to its casters.\r\n\r\n- Minerva is ideal for giving a touch of elegance and originality to your living room\r\n\r\n- Perfect to accompany an aperitif or after dinner with friends or work colleagues\r\n\r\n- It decorates classic style environments but does not exclude modern locations either\r\n\r\n- Suitable for those with limited spaces (for greater enhancement of the product we suggest placing it in an area of ​​at least 70 x 70 cm)\r\n\r\n- The &quot;Minerva&quot; globe is also perfect as a gift idea to amaze!\r\n\r\nBar accessories not included.', 3, 4, 2, 'https://www.bronze-sculpture-art.com/wp-content/uploads/sites/2/2020/09/drewniany-barek-globus-zoffoli-.jpg', '- Total dimensions: 50 × 50 × h 93 cm\r\n\r\n- Sphere diameter: 40 cm, the inner compartment can accommodate up to 9 glasses with a maximum diameter of 8 cm and 2/3 bottles. Additional bottles can be stored on the lower base.\r\n\r\n- 16th century map\r\n\r\n- Color: blue ocean\r\n\r\n- Structure: 4 legs in beech wood, north and south meridian, internal revolving bottle rack, wheels'),
(9, 'Hunter\'s Trophy Antler Wall Décor', 3400, 'This deer antler skull cap mount is a large piece of taxidermy home decor. Many other skull caps are small, but this one is large! This antler wall mount is a perfect size and truly high quality, attractive piece of art. This piece is sure to please. This antler mount has been crafted with high-quality resin. This plaque is perfect for a man cave, bar, or above a bedroom headboard or fireplace.', 3, 5, 3, 'https://secure.img1-fg.wfcdn.com/im/10039311/resize-h800-w800%5Ecompr-r85/1381/138165651/Hunter%27s+Trophy+Antler+Wall+D%C3%A9cor.jpg', 'Color: Gray\r\nWall Mounting Hardware Included: Yes\r\nOutdoor Use: No\r\nResidential Use\r\nOrientation: Vertical Only\r\nCountry of Origin: Made in USA of Imported Materials'),
(10, 'Taxidermy Moose Skull Wall Décor', 2895, 'This Taxidermy Moose Skull Wall Décor is truly unique. This piece brings rustic animal decor to the modern age and will be sure to spark conversation in your home. Hang this moose skull in your living space, bedroom, entryway, or office and show off your decor style!', 3, 5, 1, 'https://secure.img1-fg.wfcdn.com/im/41565293/resize-h800-w800%5Ecompr-r85/1382/138246453/Faux+Taxidermy+Moose+Skull+Wall+D%C3%A9cor.jpg', 'Size: 20.5\'\' H x 23\'\' W x 7\'\' D\r\nWeight: 5 lb.\r\nOutdoor Use: No\r\nOrientation: Vertical Only\r\nCountry of Origin: United States'),
(11, 'Whiskey Barrel Bar', 7995, 'Whiskey Barrel Bars are for those who want to add that IT factor in your bar area.  Have you been told to get your liquor out of your kitchen?  Not enough room on your bar shelves?  Add this to your bar area and all of your friends will be jealous!  The whiskey barrel bar can include a door and even lighting. This item can be cleared by either a poly or water based clear coat.', 3, 5, 12, 'https://www.baukolsbarrels.com/wp-content/uploads/2020/11/Whiskey-Barrel-Bar-5-600x636.jpeg', '***Whiskey Barrels vary in color and age. Because of this, we can not guarantee the color for each barrel.'),
(12, 'Skull &amp; Antler Art Print', 800, 'Skull &amp; Antler Study I by Ethan Harper, Art Print\r\nART PRINTS are produced on professional-grade paper using high-end equipment to yield a gallery-quality product with stunning vibrancy.', 3, 6, 20, 'https://images.barewalls.com/bwcomp/art-print-poster/bw1232660/skull-antler-study-i.jpg?units=cm&amp;pw=60.96&amp;ph=81.28', 'Open Edition Giclee - Matte - Art Print'),
(13, 'American Trophy III Art Print', 800, 'American Trophy III by John Butler, Art Print\r\nART PRINTS are produced on professional-grade paper using high-end equipment to yield a gallery-quality product with stunning vibrancy.', 3, 6, 20, 'https://images.barewalls.com/bwcomp/art-print-poster/bw1191909/american-trophy-iii.jpg?units=cm&amp;pw=55.88&amp;ph=76.2', 'Open Edition Giclee - Matte - Art Print'),
(14, 'American Trophy II Art Print', 800, 'American Trophy II by John Butler, Art Print\r\nART PRINTS are produced on professional-grade paper using high-end equipment to yield a gallery-quality product with stunning vibrancy.', 3, 6, 20, 'https://images.barewalls.com/bwcomp/art-print-poster/bw1191777/american-trophy-ii.jpg?units=cm&amp;pw=55.88&amp;ph=76.2', 'Open Edition Giclee - Matte - Art Print'),
(15, 'Beer - The Reason, Art Print', 300, 'ART PRINTS are produced on professional-grade paper using high-end equipment to yield a gallery-quality product with stunning vibrancy.', 3, 6, 40, 'https://cdn.shopify.com/s/files/1/0756/6205/products/The.Reason.Dark_2048x2048.jpg?v=1605978357', ''),
(16, 'Beer - The Reason, Metal Novelty', 495, 'Beer Sign The Reason I Wake Up Every Afternoon Metal Novelty Sign\r\n\r\nThis sign features rolled edges, embossed features and weatherproof finish for indoor or outdoor use.', 3, 6, 50, 'https://images-na.ssl-images-amazon.com/images/I/71WU%2BurAJgL._AC_SY679_.jpg', 'Measures 12&quot; x 16-3/4&quot; and has pre-drilled holes on each corner for hanging.'),
(17, 'Nintendo Entertainment System™', 229, 'Do you love video games? Did you play Super Mario Bros.™ back in the day? Or do you just enjoy a hands-on, creative activity in your spare time? If so, this nostalgic LEGO® Nintendo Entertainment System™ (71374) model kit is perfect for you. The brick-built NES is packed with realistic details, including an opening slot for the Game Pak with a locking function and a controller with a connecting cable and plug.', 1, 1, 20, 'https://www.pricerunner.se/product/640x640/3000576813/Lego-Super-Mario-Nintendo-Entertainment-System-71374.jpg', 'Item: 71374,\r\n      Pieces: 2646,\r\n      Dimensions:\r\n      H: 10&quot; (23cm)\r\n      W: 10&quot; (24cm)\r\n      D: 7&quot; (16cm)'),
(18, 'Liebherr R 9800 Excavator', 449, 'Get ready for a colossal LEGO® build and play experience with the 4,108-piece LEGO Technic™ Liebherr R 9800 Excavator. Developed in partnership with Liebherr, this replica model is operated via the intuitive LEGO TECHNIC CONTROL+ app and powered by 2 advanced Smart Hubs with 7 motors. The sophisticated app technology enables super-precise movement and amazing functionality, while delivering endless authentic digital play combinations via 4 different control screens with cool graphics. The Multi-function control screen enables users to drive the machine in all directions, rotate the superstructure, extend and raise the boom, open and tilt the bucket, play realistic sound effects and get real-time feedback, such as boom position, power usage and drive distance.', 1, 1, 20, 'https://www.pricerunner.se/product/640x640/1908396539/Lego-Technic-Liebherr-R-9800-Graevmaskin-42100.jpg', 'Item: 42100,\r\n      Pieces: 4108,\r\n      Dimensions:\r\n      H: 15&quot; (39cm)\r\n      W: 10&quot; (27cm)\r\n      D: 25&quot; (65cm)'),
(19, '6x6 Volvo Articulated Hauler', 249, 'A realistic model version of Volvo’s biggest articulated hauler, the LEGO® Technic™ 6x6 Volvo Articulated Hauler (42114) is ideal for kids who love construction vehicles. The truck is powered by 1 large angular position motor, 1 XL motor and 1 L motor with a Bluetooth controlled Smart Hub for realistic functionality.', 1, 1, 20, 'https://www.pricerunner.se/product/640x640/3000505032/Lego-Technic-Volvo-6x6-Articulated-Truck-42114.jpg', 'Item: 42114,\r\n      Pieces: 2193,\r\n      Dimensions:\r\n      H: 7&quot; (20cm)\r\n      W: 7&quot; (20cm)\r\n      D: 23&quot; (60cm)'),
(20, 'Porsche 911', 149, 'Celebrate the unmistakable style of Porsche with this fantastic building challenge as you create your own classic, collectible car with the LEGO® Porsche 911 (10295) model kit. Choose to build either the Turbo model with its turbocharged engine or the Targa with its iconic Targa bar and a removable roof that stores under the hood.', 1, 1, 20, 'https://www.pricerunner.se/product/640x640/3001180357/Lego-Creator-Porsche-911-10295.jpg', 'Item: 10295,\r\n      Pieces: 1458,\r\n      Dimensions:\r\n      H: 4&quot; (10cm)\r\n      W: 14&quot; (35cm)\r\n      D: 7&quot; (16cm)'),
(21, 'Dom\'s Dodge Charger', 99, 'Give fans of Fast &amp; Furious the ultimate thrill with this LEGO® Technic™ Dom’s Dodge Charger (42111) building set for kids and adults. Based on the iconic 1970s Dodge Charger R/T, it’s packed with authentic details. The opening hood reveals a model version of the iconic V8 engine. Other cool features include moving pistons, wishbone suspension, steering system and air blower. There are even nitro bottles in the trunk to bring Dom’s daring high-speed chases to life. Just like the real thing!', 1, 1, 20, 'https://www.pricerunner.se/product/640x640/3000259155/Lego-Technic-Dom-s-Dodge-Charger-42111.jpg', 'Item: 42111,\r\n      Pieces: 1077,\r\n      Dimensions:\r\n      H: 4&quot; (11cm)\r\n      W: 6&quot; (16cm)\r\n      D: 15&quot; (39cm)'),
(22, '4X4 X-treme Off-Roader', 249, 'Are you ready to take your LEGO® play experience to another level? The LEGO Technic™ 4x4 X-treme Off-Roader is powered by an advanced Smart Hub with 3 motors and controlled via the intuitive LEGO TECHNIC CONTROL+ app. The sophisticated app technology enables super-precise movement and functionality, while delivering endless authentic digital play combinations with authentic sound effects. Users can choose from different control screens to drive forward, reverse, steer, accelerate, brake and traverse obstacles.', 1, 1, 20, 'https://www.pricerunner.se/product/640x640/1903317861/Lego-Technic-4x4-X-Treme-Off-Roader-42099.jpg', 'Item: 42099,\r\n      Pieces: 958,\r\n      Dimensions:\r\n      H: 7&quot; (19cm)\r\n      W: 8&quot; (22cm)\r\n      D: 12&quot; (33cm)'),
(23, 'Robot Inventor', 359, 'Enter the amazing physical and digital world of programmable, remote-control robots and intelligent creations. With LEGO® MINDSTORMS® Robot Inventor (51515), young robot fans build 5 unique, motorized robots and vehicles using the free LEGO MINDSTORMS Robot Inventor App. Then they bring them to life, one at a time, using the drag-and-drop coding environment based on Scratch and complete fun activities and challenging missions (visit LEGO.com/devicecheck for a list of compatible devices). And with almost 1,000 pieces, kids will love to come up with their own tech toy creations and share them with other robot fans on LEGO Life.', 1, 1, 20, 'https://www.pricerunner.se/product/640x640/3000646177/Lego-Mindstorms-Robot-Inventor-51515.jpg', 'Item: 51515,\r\n      Pieces: 949'),
(24, 'LEGO® MINDSTORMS® EV3', 339, 'Combining the versatility of the LEGO® building system with the most advanced technology we\'ve ever developed, LEGO MINDSTORMS® EV3 lets you unleash a world of walking, talking and thinking robots that do anything you can imagine. Complete a series of challenging missions using the intuitive icon-based EV3 Programmer App for tablet devices to build and program TRACK3R, R3PTAR, SPIK3R, EV3RSTORM and GRIPP3R, and then create your own programs. Take your robotics skills to the next level with the companion EV3 Software for PC and Mac, with its more advanced yet familiar programming interface. For instant control, download the free Robot Commander app for smart devices or use the infrared remote control included with each set.', 1, 1, 20, 'https://www.pricerunner.se/product/640x640/1554156506/Lego-Mindstorms-EV3-31313.jpg', 'Item: 31313,\r\n      Pieces: 601');

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE `statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `order_contents`
--
ALTER TABLE `order_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_contents`
--
ALTER TABLE `order_contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `order_contents`
--
ALTER TABLE `order_contents`
  ADD CONSTRAINT `order_contents_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_contents_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
