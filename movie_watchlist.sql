-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2025 at 12:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `movie_watchlist`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password_hash`, `created_at`) VALUES
(2, 'admin', '$2y$10$MQ1ZK2/GGmyNu4d1GUHH0umyXeERzYMFQi4/7CuWY7lVZ5PMNUi5u', '2025-10-28 19:17:30');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `release_year` year(4) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `poster_url` varchar(255) DEFAULT NULL,
  `netflix_url` varchar(255) DEFAULT NULL,
  `prime_url` varchar(255) DEFAULT NULL,
  `disney_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `title`, `genre`, `description`, `release_year`, `created_at`, `poster_url`, `netflix_url`, `prime_url`, `disney_url`) VALUES
(1, 'Inception', 'Science Fiction', 'A skilled thief enters people’s dreams to plant an idea, but the mission turns into a layered fight between reality and subconscious.', '2010', '2025-10-27 11:26:45', 'uploads/inception.jpg', 'https://sflix.fi/watch/inception.1kb681', '', ''),
(2, 'The Dark Knight', 'Action', 'Batman faces the Joker, a chaotic criminal who pushes Gotham — and Bruce Wayne — to their moral limits.', '2008', '2025-10-27 11:26:45', 'uploads/dark_knight.jpg', 'https://www.bing.com/ck/a?!&&p=ed6bd9c1b5b64f8720f7f23494a2685edffb6fe99f0185c85127e0f5370302feJmltdHM9MTc2NDExNTIwMA&ptn=3&ver=2&hsh=4&fclid=2ee288b0-2e70-656f-21e7-9ecd2f3d64df&psq=netflix+link&u=a1aHR0cHM6Ly93d3cubmV0ZmxpeC5jb20v', '', ''),
(3, 'Interstellar', 'Science Fiction', 'A group of astronauts travels through a wormhole to find a new home for humanity while a father fights to return to his daughter.', '2014', '2025-10-27 11:26:45', 'uploads/interstellar.jpg', 'https://www.netflix.com/browse', '', ''),
(4, 'Spider-Man: Into the Spider-Verse', 'Animation', 'Teen Miles Morales becomes Spider-Man and meets heroes from other dimensions to stop a multiverse threat.', '2018', '2025-10-27 11:26:45', 'uploads/spiderverse.jpg', '', '', 'https://flixhq.to/watch-movie/watch-spiderman-into-the-spiderverse-19847.5349001'),
(5, 'The Godfather', 'Crime', 'The Corleone family navigates loyalty, power, and violence as Michael reluctantly takes over the mafia empire.', '1972', '2025-10-27 11:26:45', 'uploads/godfather.jpg', 'https://flixhq.to/watch-movie/watch-the-godfather-19629.5297527', '', ''),
(6, 'Dune: Part One', 'Science Fiction', 'Paul Atreides follows his destiny on the desert planet Arrakis, where rival houses fight over the precious spice.', '2021', '2025-10-27 11:26:45', 'uploads/dune.jpg', '', 'https://flixhq.to/watch-movie/watch-dune-6752.4792159', ''),
(7, 'Inside Out', 'Animation', 'Inside a young girl’s mind, emotions like Joy and Sadness learn to work together as she adjusts to big life changes.', '2015', '2025-10-27 11:26:45', 'uploads/inside_out.jpg', '', '', 'https://flixhq.to/movie/watch-inside-out-19725'),
(8, 'Parasite', 'Thriller', 'A poor family cleverly infiltrates a rich household, but their plan unravels into dark, unexpected consequences.', '2019', '2025-10-27 11:26:45', 'uploads/parasite.jpg', '', '', 'https://flixhq.to/movie/watch-parasite-41796'),
(9, 'Hera Pheri', 'Comedy', 'Three men looking for easy money get tangled in a hilarious kidnapping mix-up.', '2000', '2025-10-27 16:05:21', 'uploads/hera_pheri.jpg', '', 'https://flixhq.to/movie/watch-hera-pheri-15548', ''),
(10, 'Phir Hera Pheri', 'Comedy', 'After getting rich, the trio goes after a bigger scheme, only to land in even bigger trouble.', '2006', '2025-10-27 16:05:21', 'uploads/phir_hera_pheri.jpg', '', 'https://flixhq.to/movie/watch-phir-hera-pheri-65064', ''),
(11, '3 Idiots', 'Comedy-Drama', 'Three friends recall their college days and an unconventional classmate who taught them to follow passion over pressure.', '2009', '2025-10-27 16:05:21', 'uploads/3_idiots.jpg', 'https://flixhq.to/watch-movie/watch-3-idiots-69690.5542369', '', ''),
(12, 'Lagaan', 'Historical Sports Drama', 'Villagers in British India challenge officers to a cricket match to escape heavy taxes.', '2001', '2025-10-27 16:05:21', 'uploads/lagaan.jpg', '', 'https://flixhq.to/movie/watch-lagaan-once-upon-a-time-in-india-9896', ''),
(13, 'Zindagi Na Milegi Dobara', 'Adventure/Drama', 'Three friends go on a road trip across Spain, facing fears, fixing friendships, and finding love.', '2011', '2025-10-27 16:05:21', 'uploads/znmd.jpg', 'https://flixhq.to/movie/watch-zindagi-na-milegi-dobara-10864', '', ''),
(14, 'Gully Boy', 'Musical Drama', 'A young man from Mumbai’s slums discovers his voice through rap and fights for a life beyond his circumstances.', '2019', '2025-10-27 16:05:21', 'uploads/gully_boy.jpg', 'https://flixhq.to/movie/watch-gully-boy-5169', '', ''),
(15, 'Drishyam', 'Thriller', 'After an accidental crime, a clever family man uses his movie knowledge to outsmart the police and protect his family.', '2015', '2025-10-27 16:05:21', 'uploads/drishyam.jpg', '', 'https://flixhq.to/movie/watch-drishyam-7581', ''),
(16, 'Dil Chahta Hai', 'Comedy-Drama', 'Three friends with very different personalities navigate love and adulthood, testing the strength of their bond.', '2002', '2025-10-27 16:05:21', 'uploads/dil_chahta_hai.jpg', '', 'https://flixhq.to/movie/watch-dil-chahta-hai-6214', ''),
(17, 'Andhadhun', 'Thriller', 'A pianist pretending to be blind witnesses a crime and gets pulled into a twisting chain of lies.', '2018', '2025-10-27 16:05:21', 'uploads/andhadhun.jpg', 'https://flixhq.to/movie/watch-andhadhun-17457', '', ''),
(18, 'Queen', 'Comedy-Drama', 'A shy girl is dumped before her wedding, so she goes on the honeymoon alone and discovers her independence.', '2014', '2025-10-27 16:05:21', 'uploads/queen.jpg', 'https://flixhq.to/movie/watch-queen-5712', '', ''),
(19, 'Chhichhore', 'Comedy-Drama', 'Old college friends reunite to tell a story of failure, friendship, and second chances to inspire a young boy.', '2019', '2025-10-27 16:05:21', 'uploads/chhichhore.jpg', 'https://flixhq.to/movie/watch-chhaava-123154', '', ''),
(20, 'Kabir Singh', 'Romantic Drama', 'A brilliant but hot-headed surgeon spirals out of control after losing the woman he loves.', '2019', '2025-10-27 16:05:21', 'uploads/kabir_singh.jpg', 'https://flixhq.to/movie/watch-kabir-singh-41444', '', ''),
(21, 'Tumbbad', 'Horror/Fantasy', 'A man obsessed with hidden treasure enters a cursed village and faces the consequences of greed.', '2018', '2025-10-27 16:05:21', 'uploads/tumbbad.jpg', 'https://flixhq.to/movie/watch-tumbbad-8118', '', ''),
(22, 'Barfi!', 'Romantic Comedy', 'A charming deaf-mute boy and two women cross paths in a sweet, emotional story about imperfect love.', '2012', '2025-10-27 16:05:21', 'uploads/barfi.jpg', '', 'https://flixhq.to/movie/watch-barfi-12561', ''),
(23, 'Dangal', 'Sports Drama', 'A former wrestler trains his daughters to become champions and challenge gender norms in India.', '2016', '2025-10-27 16:05:21', 'uploads/dangal.jpg', 'https://flixhq.to/watch-movie/watch-dangal-19629.5297527', '', ''),
(24, 'Bhaag Milkha Bhaag', 'Biographical Sports', 'Based on athlete Milkha Singh, the film follows his rise from tragedy to becoming a national sprinting hero.', '2013', '2025-10-27 16:05:21', 'uploads/bhaag_milkha_bhaag.jpg', 'https://flixhq.to/movie/watch-bhaag-milkha-bhaag-13013', '', ''),
(25, 'Piku', 'Comedy-Drama', 'A quirky father-daughter duo go on a road trip where everyday irritations reveal deep affection.', '2015', '2025-10-27 16:05:21', 'uploads/piku.jpg', 'https://flixhq.to/movie/watch-piku-15821', '', ''),
(26, 'Avatar', 'Science Fiction', 'A paraplegic marine on Pandora must choose between his human orders and the alien world he grows to love.', '2009', '2025-10-27 16:05:43', 'uploads/avatar.jpg', '', '', 'https://flixhq.to/movie/watch-avatar-19690'),
(27, 'The Shawshank Redemption', 'Drama', 'A banker wrongly imprisoned forms a lifelong friendship and quietly plans his escape and freedom.', '1994', '2025-10-27 16:05:43', 'uploads/shawshank_redemption.jpg', 'https://flixhq.to/movie/watch-the-shawshank-redemption-19679', '', ''),
(28, 'Gladiator', 'Action/Drama', 'A betrayed Roman general becomes a gladiator and fights his way toward revenge against a corrupt emperor.', '2000', '2025-10-27 16:05:43', 'uploads/gladiator.jpg', 'https://flixhq.to/movie/watch-gladiator-19456', '', ''),
(29, 'Titanic', 'Romance/Drama', 'Two people from different classes fall in love aboard the doomed RMS Titanic.', '1997', '2025-10-27 16:05:43', 'uploads/titanic.jpg', 'https://flixhq.to/watch-movie/watch-titanic-19586.5297602', '', ''),
(30, 'The Matrix', 'Science Fiction', 'A hacker discovers reality is a simulation and is chosen to fight the machines controlling humanity.', '1999', '2025-10-27 16:05:43', 'uploads/matrix.jpg', 'https://flixhq.to/movie/watch-the-matrix-19724', '', ''),
(31, 'Joker', 'Psychological Thriller', 'A lonely man in Gotham, beaten down by society, slowly transforms into the infamous criminal Joker.', '2019', '2025-10-27 16:05:43', 'uploads/joker.jpg', '', 'https://flixhq.to/movie/watch-joker-9766', ''),
(32, 'Oppenheimer', 'Historical Drama', 'The brilliant but burdened physicist J. Robert Oppenheimer leads the creation of the atomic bomb and faces its moral weight.', '2023', '2025-10-27 16:05:43', 'uploads/oppenheimer.jpg', 'https://flixhq.to/movie/watch-oppenheimer-98446', '', ''),
(33, 'Top Gun: Maverick', 'Action', 'Veteran pilot Maverick returns to train a new generation of flyers while confronting his past.', '2022', '2025-10-27 16:05:43', 'uploads/top_gun_maverick.jpg', '', 'https://flixhq.to/movie/watch-top-gun-maverick-5448', ''),
(34, 'The Wolf of Wall Street', 'Comedy/Drama', 'A stockbroker rises through fraud, excess, and wild parties — and eventually faces the fallout.', '2013', '2025-10-27 16:05:43', 'uploads/wolf_of_wall_street.jpg', 'https://flixhq.to/movie/watch-the-wolf-of-wall-street-19543', '', ''),
(35, 'The Avengers', 'Action/Superhero', 'Earth’s mightiest heroes team up to stop Loki and an alien invasion threatening the planet.', '2012', '2025-10-27 16:05:43', 'uploads/avengers.jpg', '', '', 'https://flixhq.to/movie/watch-the-avengers-19782'),
(36, 'Pathaan', 'Action', 'An Indian spy comes out of exile to stop a deadly bio-weapon threat against his country.', '2023', '2025-10-27 16:05:43', 'uploads/pathaan.jpg', 'https://flixhq.to/movie/watch-pathaan-94630', '', ''),
(37, 'Jawan', 'Action/Thriller', 'A mysterious vigilante targets corruption and joins forces with a badass women’s team to right old wrongs.', '2023', '2025-10-27 16:05:43', 'uploads/jawan.jpg', 'https://www.netflix.com/title/81493411', '', ''),
(38, 'Brahmastra: Part One – Shiva', 'Fantasy/Adventure', 'A young man who can control fire discovers he’s part of an ancient world of astras and must protect a powerful artifact.', '2022', '2025-10-27 16:05:43', 'uploads/brahmastra.jpg', 'https://flixhq.to/movie/watch-brahmastra-41699', '', ''),
(39, 'RRR', 'Action/Drama', 'Two freedom fighters in colonial India forge a fiery friendship and take on the empire in epic style.', '2022', '2025-10-27 16:05:43', 'uploads/rrr.jpg', 'https://flixhq.to/movie/watch-rrr-94651', '', ''),
(40, 'KGF: Chapter 2', 'Action', 'Rocky rises as the king of Kolar Gold Fields and battles powerful enemies to protect his empire.', '2022', '2025-10-27 16:05:43', 'uploads/kgf2.jpg', '', 'https://www.primevideo.com/detail/KGF-Chapter-2-Hindi/0OLFV66669RCQ9BSC7YCWOZA6S', ''),
(41, 'Pushpa: The Rise', 'Action/Drama', 'A fearless laborer climbs the red sandalwood smuggling chain, making dangerous enemies along the way.', '2021', '2025-10-27 16:05:43', 'uploads/pushpa.jpg', '', 'https://www.primevideo.com/-/da/detail/Pushpa-The-Rise-Telugu/0JSVEYV0WDR309IGE4QL8LJZEQ', ''),
(42, 'Article 15', 'Crime/Drama', 'A principled cop investigates a caste-based crime and confronts the deep inequalities in rural India.', '2019', '2025-10-27 16:05:43', 'uploads/article15.jpg', 'https://flixhq.to/movie/watch-article-15-47242', '', ''),
(43, 'Stree', 'Horror/Comedy', 'A small town is haunted by a female spirit that kidnaps men at night, and the locals must solve the mystery.', '2018', '2025-10-27 16:05:43', 'uploads/stree.jpg', 'https://flixhq.to/watch-movie/watch-stree-6999.5363113', '', ''),
(44, 'Uri: The Surgical Strike', 'Action/War', 'Based on true events, Indian special forces carry out a high-risk operation after a terror attack.', '2019', '2025-10-27 16:05:43', 'uploads/uri.jpg', 'https://flixhq.to/movie/watch-uri-the-surgical-strike-17151', '', ''),
(45, 'Badhaai Ho', 'Comedy/Drama', 'A grown man is embarrassed when his middle-aged parents announce a surprise pregnancy, leading to sweet family chaos.', '2018', '2025-10-27 16:05:43', 'uploads/badhaai_ho.jpg', 'https://flixhq.to/movie/watch-badhaai-ho-10781', '', ''),
(46, 'Avengers: Endgame', 'Action/Superhero', 'The Avengers unite for one final mission to undo the devastation caused by Thanos.', '2019', '2025-11-05 10:05:02', 'uploads/avengers_endgame.jpg', '', '', 'https://www.disneyplus.com/da-dk/browse/entity-b39aa962-be56-4b09-a536-98617031717f'),
(47, 'The Lion King', 'Animation/Adventure', 'A young lion prince flees his kingdom after his father’s death, learning about courage and destiny.', '1994', '2025-11-05 10:05:02', 'uploads/lion_king.jpg', '', '', 'https://www.disneyplus.com/browse/entity-87524f44-a8ea-4b08-b4d8-39103bed3eaa'),
(48, 'Frozen', 'Animation/Musical', 'Two sisters must face their fears and find true love as an eternal winter grips their kingdom.', '2013', '2025-11-05 10:05:02', 'uploads/frozen.jpg', '', '', 'https://flixhq.to/movie/watch-frozen-19753'),
(49, 'Black Panther', 'Action/Superhero', 'T’Challa returns home to Wakanda to claim the throne but faces a powerful challenger.', '2018', '2025-11-05 10:05:02', 'uploads/black_panther.jpg', '', '', 'https://flixhq.to/movie/watch-black-panther-19797'),
(50, 'Avengers: Infinity War', 'Action/Superhero', 'The Avengers and their allies attempt to stop Thanos from collecting the Infinity Stones.', '2018', '2025-11-05 10:05:02', 'uploads/infinity_war.jpg', '', '', 'https://flixhq.to/movie/watch-avengers-infinity-war-19851'),
(51, 'Doctor Strange', 'Fantasy/Action', 'A brilliant but arrogant surgeon learns the mystic arts after an accident changes his life.', '2016', '2025-11-05 10:05:02', 'uploads/doctor_strange.jpg', '', '', 'https://flixhq.to/movie/watch-doctor-strange-19707'),
(52, 'Guardians of the Galaxy', 'Action/Adventure', 'A band of intergalactic misfits team up to protect a powerful orb from falling into evil hands.', '2014', '2025-11-05 10:05:02', 'uploads/guardians_galaxy.jpg', '', '', 'https://flixhq.to/movie/watch-guardians-of-the-galaxy-19781'),
(53, 'Iron Man', 'Action/Superhero', 'A genius billionaire builds a high-tech suit of armor and becomes the armored hero Iron Man.', '2008', '2025-11-05 10:05:02', 'uploads/iron_man.jpg', '', '', 'https://flixhq.to/movie/watch-iron-man-19680'),
(54, 'Captain America: The Winter Soldier', 'Action/Superhero', 'Captain America uncovers a conspiracy within S.H.I.E.L.D. while facing a deadly assassin.', '2014', '2025-11-05 10:05:02', 'uploads/winter_soldier.jpg', '', '', 'https://flixhq.to/movie/watch-captain-america-the-winter-soldier-18744'),
(55, 'The Incredible Hulk', 'Action/Superhero', 'Scientist Bruce Banner must evade the military while controlling the monster within.', '2008', '2025-11-05 10:05:02', 'uploads/hulk.jpg', '', '', 'https://flixhq.to/movie/watch-the-incredible-hulk-19338'),
(56, 'Avatar: The Way of Water', 'Science Fiction', 'Jake Sully and Neytiri protect their family as new threats rise on Pandora.', '2022', '2025-11-05 10:05:02', 'uploads/avatar_way_of_water.jpg', '', '', 'https://flixhq.to/movie/watch-avatar-the-way-of-water-79936'),
(57, 'The Batman', 'Action/Thriller', 'Batman faces the Riddler while uncovering corruption in Gotham City during his second year as a vigilante.', '2022', '2025-11-05 10:05:02', 'uploads/the_batman.jpg', 'https://flixhq.to/movie/watch-the-batman-16076', '', ''),
(58, 'Mission: Impossible – Fallout', 'Action/Thriller', 'Ethan Hunt and his team race against time after a mission goes wrong.', '2018', '2025-11-05 10:05:02', 'uploads/mission_fallout.jpg', 'https://flixhq.to/movie/watch-mission-impossible-fallout-19811', '', ''),
(59, 'The Super Mario Bros. Movie', 'Animation/Adventure', 'Mario and Luigi enter the Mushroom Kingdom to save the world from Bowser.', '2023', '2025-11-05 10:05:02', 'uploads/mario_movie.jpg', '', '', 'https://flixhq.to/movie/watch-the-super-mario-bros-movie-95023'),
(60, 'Deadpool', 'Action/Comedy', 'A wise-cracking mercenary with healing powers hunts down the man who destroyed his life.', '2016', '2025-11-05 10:05:02', 'uploads/deadpool.jpg', '', '', 'https://flixhq.to/movie/watch-deadpool-19694'),
(61, 'The Godfather: Part II', 'Crime/Drama', 'The saga continues as Michael Corleone expands the family empire while facing betrayal and guilt.', '1974', '2025-11-05 10:11:04', 'uploads/godfather2.jpg', 'https://www.netflix.com/dk/title/60011663', '', ''),
(62, 'Schindler’s List', 'Historical/Drama', 'A German businessman saves hundreds of Jews during the Holocaust through courage and compassion.', '1993', '2025-11-05 10:11:04', 'uploads/schindlers_list.jpg', '', 'https://flixhq.to/movie/watch-schindlers-list-19786', ''),
(63, 'Saving Private Ryan', 'War/Drama', 'After D-Day, a team of soldiers risks their lives to bring one man safely home.', '1998', '2025-11-05 10:11:04', 'uploads/saving_private_ryan.jpg', 'https://www.netflix.com/dk-en/title/21878564', '', ''),
(64, 'The Departed', 'Crime/Thriller', 'An undercover cop and a mole in the police try to uncover each other in a deadly game of deception.', '2006', '2025-11-05 10:11:04', 'uploads/the_departed.jpg', 'https://www.netflix.com/title/70044689', '', ''),
(65, 'The Pursuit of Happyness', 'Drama/Biography', 'A struggling salesman and his young son fight against odds to find stability and success.', '2006', '2025-11-05 10:11:04', 'uploads/pursuit_of_happyness.jpg', 'https://www.netflix.com/title/70044605', '', ''),
(66, 'American Sniper', 'War/Biography', 'The true story of U.S. Navy SEAL Chris Kyle, America’s most lethal sniper, and his internal struggles.', '2014', '2025-11-05 10:11:04', 'uploads/american_sniper.jpg', '', 'https://flixhq.to/movie/watch-american-sniper-19525', ''),
(67, 'Mad Max: Fury Road', 'Action/Adventure', 'In a post-apocalyptic desert, Max and Furiosa fight to survive against warlords in high-octane chaos.', '2015', '2025-11-05 10:11:04', 'uploads/mad_max_fury_road.jpg', 'https://flixhq.to/movie/watch-mad-max-fury-road-19677', '', ''),
(68, 'The Martian', 'Science Fiction', 'Stranded on Mars, an astronaut uses ingenuity and humor to survive until rescue arrives.', '2015', '2025-11-05 10:11:04', 'uploads/the_martian.jpg', 'https://flixhq.to/watch-movie/watch-the-martian-19709.5297428', '', ''),
(69, 'Whiplash', 'Drama/Music', 'A young drummer pushes his limits under the brutal guidance of a perfectionist instructor.', '2014', '2025-11-05 10:11:04', 'uploads/whiplash.jpg', '', 'https://flixhq.to/movie/watch-whiplash-19644', ''),
(70, 'La La Land', 'Romantic Musical', 'A jazz musician and an actress fall in love while pursuing their dreams in Los Angeles.', '2016', '2025-11-05 10:11:04', 'uploads/la_la_land.jpg', '', 'https://flixhq.to/movie/watch-la-la-land-19613', ''),
(71, 'Django Unchained', 'Western/Action', 'A freed slave partners with a bounty hunter to rescue his wife from a brutal plantation owner.', '2012', '2025-11-05 10:11:04', 'uploads/django_unchained.jpg', '', 'https://flixhq.to/movie/watch-django-unchained-19493', ''),
(72, 'The Hateful Eight', 'Western/Thriller', 'Eight strangers seek shelter during a blizzard, but tension and betrayal rise inside the cabin.', '2015', '2025-11-05 10:11:04', 'uploads/hateful_eight.jpg', '', 'https://flixhq.to/movie/watch-the-hateful-eight-19387', ''),
(73, 'Inglourious Basterds', 'War/Drama', 'A group of Jewish soldiers and a vengeful woman plan to assassinate Nazi leaders in occupied France.', '2009', '2025-11-05 10:11:04', 'uploads/inglourious_basterds.jpg', '', 'https://flixhq.to/movie/watch-inglourious-basterds-19585', ''),
(74, 'Shutter Island', 'Mystery/Thriller', 'A U.S. marshal investigates a disappearance at a mental asylum and uncovers dark secrets.', '2010', '2025-11-05 10:11:04', 'uploads/shutter_island.jpg', 'https://www.netflix.com/title/70095139', '', ''),
(75, 'The Grand Budapest Hotel', 'Comedy/Drama', 'The adventures of a legendary concierge and his protégé in a whimsical European hotel.', '2014', '2025-11-05 10:11:04', 'uploads/grand_budapest_hotel.jpg', '', 'https://flixhq.to/movie/watch-the-grand-budapest-hotel-19149', ''),
(76, 'Jaws', 'Thriller/Adventure', 'A giant great white shark terrorizes a seaside town, leading to a suspenseful manhunt.', '1975', '2025-11-05 10:11:04', 'uploads/jaws.jpg', '', '', 'https://flixhq.to/movie/watch-jaws-19335'),
(77, 'Back to the Future', 'Science Fiction/Adventure', 'A teenager travels back in time and must ensure his parents fall in love to save his own future.', '1985', '2025-11-05 10:11:04', 'uploads/back_to_the_future.jpg', 'https://flixhq.to/movie/watch-back-to-the-future-19554', '', ''),
(78, 'The Terminator', 'Science Fiction/Action', 'A cyborg assassin is sent from the future to kill the mother of humanity’s future savior.', '1984', '2025-11-05 10:11:04', 'uploads/terminator.jpg', 'https://flixhq.to/movie/watch-the-terminator-19536', '', ''),
(79, 'Terminator 2: Judgment Day', 'Science Fiction/Action', 'A reprogrammed cyborg defends a young boy from a more advanced killer machine.', '1991', '2025-11-05 10:11:04', 'uploads/terminator2.jpg', 'https://flixhq.to/movie/watch-terminator-2-judgment-day-19609', '', ''),
(80, 'The Silence of the Lambs', 'Thriller/Horror', 'A young FBI trainee seeks help from a brilliant but dangerous killer to catch another murderer.', '1991', '2025-11-05 10:11:04', 'uploads/silence_of_the_lambs.jpg', '', 'https://flixhq.to/movie/watch-the-silence-of-the-lambs-8603', ''),
(124, 'Kantara :The Legend', 'Action/Drama', 'Prequel of Kanatara', '2026', '2025-11-13 10:28:41', 'uploads/kantara2.jpg', 'https://hdstream4u.com/file/0n8xc19v9gni', '', ''),
(125, 'Lokha : Chapter 1: Chandra', 'Science Fiction', '', '2025', '2025-11-13 10:33:09', 'uploads/lokha.jpg', 'https://hdstream4u.com/file/5i3wwt6luj1k', '', ''),
(128, 'Stranger Things', 'Science Fiction', '', '2026', '2025-11-20 10:55:53', 'uploads/strangerthings.jpg', 'https://www.netflix.com/title/80057281', '', ''),
(129, 'The Family Man', 'Comedy-Drama', '', '2026', '2025-11-21 09:58:51', 'uploads/familyman.jpg', '', 'https://www.primevideo.com/storefront', ''),
(177, 'Test Movie Edit Invalid', 'Drama', 'Valid description', '2020', '2025-12-03 12:13:52', 'poster.jpg', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `favourite_movie` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `favourite_movie`, `created_at`) VALUES
(99, 'Sandip', 'lamsalsandip98@gmail.com', '$2y$10$P5VKT6B1Xh5clPTzPXAp/OD3cA37uFpO7Ty0.pQ/5WFxZ1U2KsVfe', 'pk', '2025-12-02 23:35:07'),
(113, '', 'logintest_ok@example.com', '$2y$10$G5T6fMRzhNed1ZUrKjpmZub/rzDKGrI9I196TAItF/ngosM5MgrmO', NULL, '2025-12-03 12:12:12'),
(115, '', 'regtest_3@example.com', '$2y$10$9a5.cvZQaiBY/1MN9s/5M.grpinrafpEZE0LzMhrzcDbVu9/8cGAC', NULL, '2025-12-03 12:20:41');

-- --------------------------------------------------------

--
-- Table structure for table `watchlist`
--

CREATE TABLE `watchlist` (
  `watchlist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `added_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `watchlist`
--

INSERT INTO `watchlist` (`watchlist_id`, `user_id`, `movie_id`, `added_at`, `updated_at`) VALUES
(58, 99, 129, '2025-12-02 23:36:29', '2025-12-02 23:36:29'),
(60, 99, 128, '2025-12-02 23:36:47', '2025-12-02 23:36:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `watchlist`
--
ALTER TABLE `watchlist`
  ADD PRIMARY KEY (`watchlist_id`),
  ADD UNIQUE KEY `uc_user_movie` (`user_id`,`movie_id`),
  ADD KEY `fk_watchlist_movie` (`movie_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `watchlist`
--
ALTER TABLE `watchlist`
  MODIFY `watchlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `watchlist`
--
ALTER TABLE `watchlist`
  ADD CONSTRAINT `fk_watchlist_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_watchlist_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
