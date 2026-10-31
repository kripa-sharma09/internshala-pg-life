CREATE DATABASE IF NOT EXISTS pglife CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pglife;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS interested_properties;
DROP TABLE IF EXISTS property_amenities;
DROP TABLE IF EXISTS amenities;
DROP TABLE IF EXISTS properties;
DROP TABLE IF EXISTS cities;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  phone VARCHAR(20) NULL,
  gender ENUM('Female','Male','Other','Prefer not to say') NOT NULL DEFAULT 'Prefer not to say',
  college VARCHAR(150) NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE cities (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE,
  slug VARCHAR(90) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE properties (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  city_id INT UNSIGNED NOT NULL,
  name VARCHAR(150) NOT NULL,
  address VARCHAR(255) NOT NULL,
  rent DECIMAL(10,2) NOT NULL,
  rating DECIMAL(2,1) NOT NULL DEFAULT 4.0,
  gender_preference ENUM('Any','Female','Male') NOT NULL DEFAULT 'Any',
  bedrooms TINYINT UNSIGNED NOT NULL DEFAULT 1,
  description TEXT NOT NULL,
  image_path VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_properties_city FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE,
  INDEX idx_properties_city (city_id),
  INDEX idx_properties_rent (rent),
  INDEX idx_properties_rating (rating)
) ENGINE=InnoDB;

CREATE TABLE amenities (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE,
  icon_path VARCHAR(255) NULL
) ENGINE=InnoDB;

CREATE TABLE property_amenities (
  property_id INT UNSIGNED NOT NULL,
  amenity_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (property_id, amenity_id),
  CONSTRAINT fk_pa_property FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
  CONSTRAINT fk_pa_amenity FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE interested_properties (
  user_id INT UNSIGNED NOT NULL,
  property_id INT UNSIGNED NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, property_id),
  CONSTRAINT fk_ip_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_ip_property FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO cities (id, name, slug) VALUES
(1,'Bengaluru','bengaluru'),
(2,'Hyderabad','hyderabad'),
(3,'Delhi','delhi'),
(4,'Mumbai','mumbai');

INSERT INTO amenities (id, name, icon_path) VALUES
(1,'Wi-Fi','img/amenities/wifi.svg'),
(2,'Air conditioning','img/amenities/ac.svg'),
(3,'Bed','img/amenities/bed.svg'),
(4,'CCTV','img/amenities/cctv.svg'),
(5,'Dining','img/amenities/dining.svg'),
(6,'Fire extinguisher','img/amenities/fireext.svg'),
(7,'Geyser','img/amenities/geyser.svg'),
(8,'Lift','img/amenities/lift.svg'),
(9,'Parking','img/amenities/parking.svg'),
(10,'Power backup','img/amenities/powerbackup.svg'),
(11,'RO water','img/amenities/rowater.svg'),
(12,'TV','img/amenities/tv.svg'),
(13,'Washing machine','img/amenities/washingmachine.svg');

INSERT INTO properties (id,city_id,name,address,rent,rating,gender_preference,bedrooms,description,image_path) VALUES
(1,1,'Sunrise Comfort PG','Koramangala, Bengaluru',8500,4.5,'Any',2,'A sample PG listing near cafes, public transport, and everyday essentials. Replace this description and image with verified property information.', 'img/properties/placeholder.svg'),
(2,1,'Green Nest Residency','HSR Layout, Bengaluru',9500,4.3,'Female',2,'A sample shared accommodation listing with essential amenities and a convenient neighbourhood.', 'img/properties/placeholder.svg'),
(3,2,'City Square Living','Madhapur, Hyderabad',7800,4.2,'Any',3,'A sample PG listing for students and professionals. Contact the property owner to confirm current availability.', 'img/properties/placeholder.svg'),
(4,2,'Lakeview Stay','Kondapur, Hyderabad',9000,4.6,'Male',2,'A sample accommodation listing. Confirm actual facilities, rules, and rent before making a decision.', 'img/properties/placeholder.svg'),
(5,3,'Metro Homes PG','Laxmi Nagar, Delhi',7000,4.1,'Any',3,'A sample city listing intended for development and testing of the PG Life project.', 'img/properties/placeholder.svg'),
(6,3,'Campus Corner Stay','GTB Nagar, Delhi',10000,4.4,'Female',2,'A sample PG close to a student-oriented neighbourhood. Replace with accurate property details.', 'img/properties/placeholder.svg'),
(7,4,'Harbour View PG','Andheri East, Mumbai',12000,4.5,'Any',2,'A sample listing for a city-based accommodation search. All prices and details are illustrative.', 'img/properties/placeholder.svg'),
(8,4,'Palm House Living','Powai, Mumbai',14500,4.7,'Female',2,'A sample listing used to test property cards, sorting, and interested-property features.', 'img/properties/placeholder.svg');

INSERT INTO property_amenities (property_id, amenity_id)
SELECT p.id, a.id FROM properties p CROSS JOIN amenities a
WHERE a.name IN ('Wi-Fi','Bed','CCTV','Dining','RO water','Power backup');
INSERT INTO property_amenities (property_id, amenity_id)
SELECT p.id, a.id FROM properties p CROSS JOIN amenities a
WHERE p.id IN (1,2,4,6,8) AND a.name IN ('Air conditioning','Washing machine','Geyser');
INSERT INTO property_amenities (property_id, amenity_id)
SELECT p.id, a.id FROM properties p CROSS JOIN amenities a
WHERE p.id IN (1,3,5,7) AND a.name IN ('Parking','TV','Fire extinguisher');
