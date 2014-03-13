USE landlor_devDB;

CREATE TABLE IF NOT EXISTS poste (
	id 			int(11) 	NOT NULL 	AUTO_INCREMENT,
	numero 		int(4) 		NOT NULL,
	type	 	enum("ACTIF", "PASSIF", "CAPITAL", "REVENU", "DEPENSE") 	NOT NULL,
	nom 		VARCHAR(100) 	NOT NULL,

	PRIMARY KEY (id),
	UNIQUE KEY numero(numero)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO poste (numero, type, nom) VALUES ('1020', 'ACTIF', 'Petite caisse');
INSERT INTO poste (numero, type, nom) VALUES ('1030', 'ACTIF', 'Paypal');
INSERT INTO poste (numero, type, nom) VALUES ('1050', 'ACTIF', 'Compte bancaire');

-- INSERT INTO poste (numero, type, nom) VALUES ('1320', 'ACTIF', 'Compte a recevoir clients');
-- INSERT INTO poste (numero, type, nom) VALUES ('1360', 'ACTIF', 'Compte a recevoir TPS');
-- INSERT INTO poste (numero, type, nom) VALUES ('1380', 'ACTIF', 'Compte a recevoir TVQ');	

INSERT INTO poste (numero, type, nom) VALUES ('1710', 'ACTIF', 'Equipement maison');
INSERT INTO poste (numero, type, nom) VALUES ('1711', 'ACTIF', 'Amortissement cumule : Equipement maison');
INSERT INTO poste (numero, type, nom) VALUES ('1720', 'ACTIF', 'Equipement informatique');
INSERT INTO poste (numero, type, nom) VALUES ('1721', 'ACTIF', 'Amortissement cumule : equipement informatique');
INSERT INTO poste (numero, type, nom) VALUES ('1780', 'ACTIF', 'Autres equipements');
INSERT INTO poste (numero, type, nom) VALUES ('1781', 'ACTIF', 'Amortissement cumule : autres equipements');

INSERT INTO poste (numero, type, nom) VALUES ('2100', 'PASSIF', 'Provisions pour travaux');
INSERT INTO poste (numero, type, nom) VALUES ('2200', 'PASSIF', 'Emprunt de banque');
INSERT INTO poste (numero, type, nom) VALUES ('2250', 'PASSIF', 'Emprunt Alexandre');
INSERT INTO poste (numero, type, nom) VALUES ('2251', 'PASSIF', 'Emprunt Thomas et Gabriela');
INSERT INTO poste (numero, type, nom) VALUES ('2252', 'PASSIF', 'Emprunt Jose Manuel');


INSERT INTO poste (numero, type, nom) VALUES ('3050', 'CAPITAL', 'Investissement Alexandre');
INSERT INTO poste (numero, type, nom) VALUES ('3051', 'CAPITAL', 'Investissement Thomas et Gabriela');

INSERT INTO poste (numero, type, nom) VALUES ('3100', 'CAPITAL', 'Avance Alexandre');
INSERT INTO poste (numero, type, nom) VALUES ('3101', 'CAPITAL', 'Avance Thomas et Gabriela');
-- INSERT INTO poste (numero, type, nom) VALUES ('3125', 'CAPITAL', 'Benefices');

INSERT INTO poste (numero, type, nom) VALUES ('4010', 'REVENU', 'Locations');
INSERT INTO poste (numero, type, nom) VALUES ('4015', 'REVENU', 'Autres revenus');
INSERT INTO poste (numero, type, nom) VALUES ('4020', 'REVENU', 'Interets placements');

INSERT INTO poste (numero, type, nom) VALUES ('5001', 'DEPENSE', 'Frais d\'entretien');
INSERT INTO poste (numero, type, nom) VALUES ('5002', 'DEPENSE', 'Achats produits entretien');
INSERT INTO poste (numero, type, nom) VALUES ('5005', 'DEPENSE', 'Electricite\, gaz\, Internet\, Telephone\, Cable');
INSERT INTO poste (numero, type, nom) VALUES ('5010', 'DEPENSE', 'Reparations');
INSERT INTO poste (numero, type, nom) VALUES ('5050', 'DEPENSE', 'Salaire Gerant');
INSERT INTO poste (numero, type, nom) VALUES ('5060', 'DEPENSE', 'Dividendes Alexandre');
INSERT INTO poste (numero, type, nom) VALUES ('5061', 'DEPENSE', 'Dividendes Thomas et Gabriela');
	
-- INSERT INTO poste (numero, type, nom) VALUES ('5100', 'DEPENSE', 'Locations bureau');
-- INSERT INTO poste (numero, type, nom) VALUES ('5111', 'DEPENSE', 'Frais de bureau a domicile incluant Assurances et Loyer');
-- INSERT INTO poste (numero, type, nom) VALUES ('5112', 'DEPENSE', 'Electricite');
-- INSERT INTO poste (numero, type, nom) VALUES ('5115', 'DEPENSE', 'Entretien et reparation');
INSERT INTO poste (numero, type, nom) VALUES ('5140', 'DEPENSE', 'Frais de bureau : Telephone \& Internet');
	
INSERT INTO poste (numero, type, nom) VALUES ('5200', 'DEPENSE', 'Frais de deplacements');
INSERT INTO poste (numero, type, nom) VALUES ('5280', 'DEPENSE', 'Entretien et reparation de vehicule');
INSERT INTO poste (numero, type, nom) VALUES ('5281', 'DEPENSE', 'Essence');
	
INSERT INTO poste (numero, type, nom) VALUES ('5300', 'DEPENSE', 'Fournitures de bureau');
	
INSERT INTO poste (numero, type, nom) VALUES ('5400', 'DEPENSE', 'Honoraires professionnels \(comptable\, Photographe\, \.\.\.\)');
	
INSERT INTO poste (numero, type, nom) VALUES ('5500', 'DEPENSE', 'Publicite et promotion');
INSERT INTO poste (numero, type, nom) VALUES ('5550', 'DEPENSE', 'Abonnements et cotisations');
INSERT INTO poste (numero, type, nom) VALUES ('5555', 'DEPENSE', 'Assurances');
	
-- INSERT INTO poste (numero, type, nom) VALUES ('5600', 'DEPENSE', 'Frais de representation');
	
INSERT INTO poste (numero, type, nom) VALUES ('5740', 'DEPENSE', 'Frais bancaires');
INSERT INTO poste (numero, type, nom) VALUES ('5760', 'DEPENSE', 'Loyers');

INSERT INTO poste (numero, type, nom) VALUES ('5910', 'DEPENSE', 'Depense d\'amortissement');
	
INSERT INTO poste (numero, type, nom) VALUES ('5900', 'DEPENSE', 'Divers');



CREATE TABLE IF NOT EXISTS operation (
	id			int(11)		NOT NULL	AUTO_INCREMENT,
	date		date		NOT NULL,
	description	VARCHAR(100)	NOT NULL,
	ref			VARCHAR(50),
	
	PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS mouvement (
	id			int(11)		NOT NULL	AUTO_INCREMENT,
	valeur		double(10,2),
	poste		int(11)		NOT NULL,
	operation	int(11)		NOT NULL,
	
	PRIMARY KEY (id),
	INDEX	(operation),
	FOREIGN KEY	(operation) REFERENCES operation(id)
		ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS clients (
	id			int(11)		NOT NULL	AUTO_INCREMENT,
	nom			VARCHAR(100)	NOT NULL,
	email		VARCHAR(100),
	adresse		TEXT,
	pays		int(11)		NOT NULL,
	tel			VARCHAR(50),
	nb_nuits	int(11)		NOT NULL,
	px_nuit		int(11),
	px_sejour	int(11)		NOT NULL,
	site		int(11),
	nb_guests	int(11)		NOT NULL,
	appt		int(11)		NOT NULL,
	date		date		NOT NULL,
	
	PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS sites (
	id			int(11)		NOT NULL	AUTO_INCREMENT,
	nom			VARCHAR(100)	NOT NULL,
	lien		VARCHAR(100),
	actif		BOOL,
	
	PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS appts (
	id			int(11)		NOT NULL	AUTO_INCREMENT,
	adresse		TEXT		NOT NULL,
	numero		VARCHAR(11),
	
	PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Structure de la table `pays`
-- 

CREATE TABLE IF NOT EXISTS `pays` (
  	`id` 		int(11) 		NOT NULL auto_increment,
  	`code_pays` varchar(3) 		collate utf8_unicode_ci NOT NULL,
  	`fr` 		varchar(200) 	collate utf8_unicode_ci NOT NULL,
  	`en` 		varchar(200) 	collate utf8_unicode_ci NOT NULL,
  	PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=239 ;

-- 
-- Contenu de la table `pays`
-- 

INSERT INTO `pays` (`id`, `code_pays`, `fr`, `en`) VALUES 
(1, 'AF', 'Afghanistan', 'Afghanistan'),
(2, 'ZA', 'Afrique du Sud', 'South Africa'),
(3, 'AL', 'Albanie', 'Albania'),
(4, 'DZ', 'Algérie', 'Algeria'),
(5, 'DE', 'Allemagne', 'Germany'),
(6, 'AD', 'Andorre', 'Andorra'),
(7, 'AO', 'Angola', 'Angola'),
(8, 'AI', 'Anguilla', 'Anguilla'),
(9, 'AQ', 'Antarctique', 'Antarctica'),
(10, 'AG', 'Antigua-et-Barbuda', 'Antigua & Barbuda'),
(11, 'AN', 'Antilles néerlandaises', 'Netherlands Antilles'),
(12, 'SA', 'Arabie saoudite', 'Saudi Arabia'),
(13, 'AR', 'Argentine', 'Argentina'),
(14, 'AM', 'Arménie', 'Armenia'),
(15, 'AW', 'Aruba', 'Aruba'),
(16, 'AU', 'Australie', 'Australia'),
(17, 'AT', 'Autriche', 'Austria'),
(18, 'AZ', 'Azerbaïdjan', 'Azerbaijan'),
(19, 'BJ', 'Bénin', 'Benin'),
(20, 'BS', 'Bahamas', 'Bahamas, The'),
(21, 'BH', 'Bahreïn', 'Bahrain'),
(22, 'BD', 'Bangladesh', 'Bangladesh'),
(23, 'BB', 'Barbade', 'Barbados'),
(24, 'PW', 'Belau', 'Palau'),
(25, 'BE', 'Belgique', 'Belgium'),
(26, 'BZ', 'Belize', 'Belize'),
(27, 'BM', 'Bermudes', 'Bermuda'),
(28, 'BT', 'Bhoutan', 'Bhutan'),
(29, 'BY', 'Biélorussie', 'Belarus'),
(30, 'MM', 'Birmanie', 'Myanmar (ex-Burma)'),
(31, 'BO', 'Bolivie', 'Bolivia'),
(32, 'BA', 'Bosnie-Herzégovine', 'Bosnia and Herzegovina'),
(33, 'BW', 'Botswana', 'Botswana'),
(34, 'BR', 'Brésil', 'Brazil'),
(35, 'BN', 'Brunei', 'Brunei Darussalam'),
(36, 'BG', 'Bulgarie', 'Bulgaria'),
(37, 'BF', 'Burkina Faso', 'Burkina Faso'),
(38, 'BI', 'Burundi', 'Burundi'),
(39, 'CI', 'Côte d''Ivoire', 'Ivory Coast (see Cote d''Ivoire)'),
(40, 'KH', 'Cambodge', 'Cambodia'),
(41, 'CM', 'Cameroun', 'Cameroon'),
(42, 'CA', 'Canada', 'Canada'),
(43, 'CV', 'Cap-Vert', 'Cape Verde'),
(44, 'CL', 'Chili', 'Chile'),
(45, 'CN', 'Chine', 'China'),
(46, 'CY', 'Chypre', 'Cyprus'),
(47, 'CO', 'Colombie', 'Colombia'),
(48, 'KM', 'Comores', 'Comoros'),
(49, 'CG', 'Congo', 'Congo'),
(50, 'KP', 'Corée du Nord', 'Korea, Demo. People''s Rep. of'),
(51, 'KR', 'Corée du Sud', 'Korea, (South) Republic of'),
(52, 'CR', 'Costa Rica', 'Costa Rica'),
(53, 'HR', 'Croatie', 'Croatia'),
(54, 'CU', 'Cuba', 'Cuba'),
(55, 'DK', 'Danemark', 'Denmark'),
(56, 'DJ', 'Djibouti', 'Djibouti'),
(57, 'DM', 'Dominique', 'Dominica'),
(58, 'EG', 'Égypte', 'Egypt'),
(59, 'AE', 'Émirats arabes unis', 'United Arab Emirates'),
(60, 'EC', 'Équateur', 'Ecuador'),
(61, 'ER', 'Érythrée', 'Eritrea'),
(62, 'ES', 'Espagne', 'Spain'),
(63, 'EE', 'Estonie', 'Estonia'),
(64, 'US', 'États-Unis', 'United States'),
(65, 'ET', 'Éthiopie', 'Ethiopia'),
(66, 'FI', 'Finlande', 'Finland'),
(67, 'FR', 'France', 'France'),
(68, 'GE', 'Géorgie', 'Georgia'),
(69, 'GA', 'Gabon', 'Gabon'),
(70, 'GM', 'Gambie', 'Gambia, the'),
(71, 'GH', 'Ghana', 'Ghana'),
(72, 'GI', 'Gibraltar', 'Gibraltar'),
(73, 'GR', 'Grèce', 'Greece'),
(74, 'GD', 'Grenade', 'Grenada'),
(75, 'GL', 'Groenland', 'Greenland'),
(76, 'GP', 'Guadeloupe', 'Guinea, Equatorial'),
(77, 'GU', 'Guam', 'Guam'),
(78, 'GT', 'Guatemala', 'Guatemala'),
(79, 'GN', 'Guinée', 'Guinea'),
(80, 'GQ', 'Guinée équatoriale', 'Equatorial Guinea'),
(81, 'GW', 'Guinée-Bissao', 'Guinea-Bissau'),
(82, 'GY', 'Guyana', 'Guyana'),
(83, 'GF', 'Guyane française', 'Guiana, French'),
(84, 'HT', 'Haïti', 'Haiti'),
(85, 'HN', 'Honduras', 'Honduras'),
(86, 'HK', 'Hong Kong', 'Hong Kong, (China)'),
(87, 'HU', 'Hongrie', 'Hungary'),
(88, 'BV', 'Ile Bouvet', 'Bouvet Island'),
(89, 'CX', 'Ile Christmas', 'Christmas Island'),
(90, 'NF', 'Ile Norfolk', 'Norfolk Island'),
(91, 'KY', 'Iles Cayman', 'Cayman Islands'),
(92, 'CK', 'Iles Cook', 'Cook Islands'),
(93, 'FO', 'Iles Féroé', 'Faroe Islands'),
(94, 'FK', 'Iles Falkland', 'Falkland Islands (Malvinas)'),
(95, 'FJ', 'Iles Fidji', 'Fiji'),
(96, 'GS', 'Iles Géorgie du Sud et Sandwich du Sud', 'S. Georgia and S. Sandwich Is.'),
(97, 'HM', 'Iles Heard et McDonald', 'Heard and McDonald Islands'),
(98, 'MH', 'Iles Marshall', 'Marshall Islands'),
(99, 'PN', 'Iles Pitcairn', 'Pitcairn Island'),
(100, 'SB', 'Iles Salomon', 'Solomon Islands'),
(101, 'SJ', 'Iles Svalbard et Jan Mayen', 'Svalbard and Jan Mayen Islands'),
(102, 'TC', 'Iles Turks-et-Caicos', 'Turks and Caicos Islands'),
(103, 'VI', 'Iles Vierges américaines', 'Virgin Islands, U.S.'),
(104, 'VG', 'Iles Vierges britanniques', 'Virgin Islands, British'),
(105, 'CC', 'Iles des Cocos (Keeling)', 'Cocos (Keeling) Islands'),
(106, 'UM', 'Iles mineures éloignées des États-Unis', 'US Minor Outlying Islands'),
(107, 'IN', 'Inde', 'India'),
(108, 'ID', 'Indonésie', 'Indonesia'),
(109, 'IR', 'Iran', 'Iran, Islamic Republic of'),
(110, 'IQ', 'Iraq', 'Iraq'),
(111, 'IE', 'Irlande', 'Ireland'),
(112, 'IS', 'Islande', 'Iceland'),
(113, 'IL', 'Israël', 'Israel'),
(114, 'IT', 'Italie', 'Italy'),
(115, 'JM', 'Jamaïque', 'Jamaica'),
(116, 'JP', 'Japon', 'Japan'),
(117, 'JO', 'Jordanie', 'Jordan'),
(118, 'KZ', 'Kazakhstan', 'Kazakhstan'),
(119, 'KE', 'Kenya', 'Kenya'),
(120, 'KG', 'Kirghizistan', 'Kyrgyzstan'),
(121, 'KI', 'Kiribati', 'Kiribati'),
(122, 'KW', 'Koweït', 'Kuwait'),
(123, 'LA', 'Laos', 'Lao People''s Democratic Republic'),
(124, 'LS', 'Lesotho', 'Lesotho'),
(125, 'LV', 'Lettonie', 'Latvia'),
(126, 'LB', 'Liban', 'Lebanon'),
(127, 'LR', 'Liberia', 'Liberia'),
(128, 'LY', 'Libye', 'Libyan Arab Jamahiriya'),
(129, 'LI', 'Liechtenstein', 'Liechtenstein'),
(130, 'LT', 'Lituanie', 'Lithuania'),
(131, 'LU', 'Luxembourg', 'Luxembourg'),
(132, 'MO', 'Macao', 'Macao, (China)'),
(133, 'MG', 'Madagascar', 'Madagascar'),
(134, 'MY', 'Malaisie', 'Malaysia'),
(135, 'MW', 'Malawi', 'Malawi'),
(136, 'MV', 'Maldives', 'Maldives'),
(137, 'ML', 'Mali', 'Mali'),
(138, 'MT', 'Malte', 'Malta'),
(139, 'MP', 'Mariannes du Nord', 'Northern Mariana Islands'),
(140, 'MA', 'Maroc', 'Morocco'),
(141, 'MQ', 'Martinique', 'Martinique'),
(142, 'MU', 'Maurice', 'Mauritius'),
(143, 'MR', 'Mauritanie', 'Mauritania'),
(144, 'YT', 'Mayotte', 'Mayotte'),
(145, 'MX', 'Mexique', 'Mexico'),
(146, 'FM', 'Micronésie', 'Micronesia, Federated States of'),
(147, 'MD', 'Moldavie', 'Moldova, Republic of'),
(148, 'MC', 'Monaco', 'Monaco'),
(149, 'MN', 'Mongolie', 'Mongolia'),
(150, 'MS', 'Montserrat', 'Montserrat'),
(151, 'MZ', 'Mozambique', 'Mozambique'),
(152, 'NP', 'Népal', 'Nepal'),
(153, 'NA', 'Namibie', 'Namibia'),
(154, 'NR', 'Nauru', 'Nauru'),
(155, 'NI', 'Nicaragua', 'Nicaragua'),
(156, 'NE', 'Niger', 'Niger'),
(157, 'NG', 'Nigeria', 'Nigeria'),
(158, 'NU', 'Nioué', 'Niue'),
(159, 'NO', 'Norvège', 'Norway'),
(160, 'NC', 'Nouvelle-Calédonie', 'New Caledonia'),
(161, 'NZ', 'Nouvelle-Zélande', 'New Zealand'),
(162, 'OM', 'Oman', 'Oman'),
(163, 'UG', 'Ouganda', 'Uganda'),
(164, 'UZ', 'Ouzbékistan', 'Uzbekistan'),
(165, 'PE', 'Pérou', 'Peru'),
(166, 'PK', 'Pakistan', 'Pakistan'),
(167, 'PA', 'Panama', 'Panama'),
(168, 'PG', 'Papouasie-Nouvelle-Guinée', 'Papua New Guinea'),
(169, 'PY', 'Paraguay', 'Paraguay'),
(170, 'NL', 'Pays-Bas', 'Netherlands'),
(171, 'PH', 'Philippines', 'Philippines'),
(172, 'PL', 'Pologne', 'Poland'),
(173, 'PF', 'Polynésie française', 'French Polynesia'),
(174, 'PR', 'Porto Rico', 'Puerto Rico'),
(175, 'PT', 'Portugal', 'Portugal'),
(176, 'QA', 'Qatar', 'Qatar'),
(177, 'CF', 'République centrafricaine', 'Central African Republic'),
(178, 'CD', 'République démocratique du Congo', 'Congo, Democratic Rep. of the'),
(179, 'DO', 'République dominicaine', 'Dominican Republic'),
(180, 'CZ', 'République tchèque', 'Czech Republic'),
(181, 'RE', 'Réunion', 'Reunion'),
(182, 'RO', 'Roumanie', 'Romania'),
(183, 'GB', 'Royaume-Uni', 'Saint Pierre and Miquelon'),
(184, 'RU', 'Russie', 'Russia (Russian Federation)'),
(185, 'RW', 'Rwanda', 'Rwanda'),
(186, 'SN', 'Sénégal', 'Senegal'),
(187, 'EH', 'Sahara occidental', 'Western Sahara'),
(188, 'KN', 'Saint-Christophe-et-Niévès', 'Saint Kitts and Nevis'),
(189, 'SM', 'Saint-Marin', 'San Marino'),
(190, 'PM', 'Saint-Pierre-et-Miquelon', 'Saint Pierre and Miquelon'),
(191, 'VA', 'Saint-Siège ', 'Vatican City State (Holy See)'),
(192, 'VC', 'Saint-Vincent-et-les-Grenadines', 'Saint Vincent and the Grenadines'),
(193, 'SH', 'Sainte-Hélène', 'Saint Helena'),
(194, 'LC', 'Sainte-Lucie', 'Saint Lucia'),
(195, 'SV', 'Salvador', 'El Salvador'),
(196, 'WS', 'Samoa', 'Samoa'),
(197, 'AS', 'Samoa américaines', 'American Samoa'),
(198, 'ST', 'Sao Tomé-et-Principe', 'Sao Tome and Principe'),
(199, 'SC', 'Seychelles', 'Seychelles'),
(200, 'SL', 'Sierra Leone', 'Sierra Leone'),
(201, 'SG', 'Singapour', 'Singapore'),
(202, 'SI', 'Slovénie', 'Slovenia'),
(203, 'SK', 'Slovaquie', 'Slovakia'),
(204, 'SO', 'Somalie', 'Somalia'),
(205, 'SD', 'Soudan', 'Sudan'),
(206, 'LK', 'Sri Lanka', 'Sri Lanka (ex-Ceilan)'),
(207, 'SE', 'Suède', 'Sweden'),
(208, 'CH', 'Suisse', 'Switzerland'),
(209, 'SR', 'Suriname', 'Suriname'),
(210, 'SZ', 'Swaziland', 'Swaziland'),
(211, 'SY', 'Syrie', 'Syrian Arab Republic'),
(212, 'TW', 'Taïwan', 'Taiwan'),
(213, 'TJ', 'Tadjikistan', 'Tajikistan'),
(214, 'TZ', 'Tanzanie', 'Tanzania, United Republic of'),
(215, 'TD', 'Tchad', 'Chad'),
(216, 'TF', 'Terres australes françaises', 'French Southern Territories - TF'),
(217, 'IO', 'Territoire britannique de l''Océan Indien', 'British Indian Ocean Territory'),
(218, 'TH', 'Thaïlande', 'Thailand'),
(219, 'TL', 'Timor Oriental', 'Timor-Leste (East Timor)'),
(220, 'TG', 'Togo', 'Togo'),
(221, 'TK', 'Tokélaou', 'Tokelau'),
(222, 'TO', 'Tonga', 'Tonga'),
(223, 'TT', 'Trinité-et-Tobago', 'Trinidad & Tobago'),
(224, 'TN', 'Tunisie', 'Tunisia'),
(225, 'TM', 'Turkménistan', 'Turkmenistan'),
(226, 'TR', 'Turquie', 'Turkey'),
(227, 'TV', 'Tuvalu', 'Tuvalu'),
(228, 'UA', 'Ukraine', 'Ukraine'),
(229, 'UY', 'Uruguay', 'Uruguay'),
(230, 'VU', 'Vanuatu', 'Vanuatu'),
(231, 'VE', 'Venezuela', 'Venezuela'),
(232, 'VN', 'ViÃªt Nam', 'Viet Nam'),
(233, 'WF', 'Wallis-et-Futuna', 'Wallis and Futuna'),
(234, 'YE', 'Yémen', 'Yemen'),
(235, 'YU', 'Yougoslavie', 'Saint Pierre and Miquelon'),
(236, 'ZM', 'Zambie', 'Zambia'),
(237, 'ZW', 'Zimbabwe', 'Zimbabwe'),
(238, 'MK', 'ex-République yougoslave de Macédoine', 'Macedonia, TFYR');