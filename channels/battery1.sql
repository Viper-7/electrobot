CREATE TABLE batteries (id INTEGER PRIMARY KEY AUTOINCREMENT,Code VARCHAR NOT NULL, Name VARCHAR, Cutoff VARCHAR, Nominal VARCHAR, MaxCharge VARCHAR, Rechargable INTEGER, Cylindrical INTEGER);
INSERT INTO batteries (id,code,name,cutoff,nominal,maxcharge,rechargable,cylindrical) VALUES (1,'ICR','Lithium Cobalt Oxide','2.5','3.7','4.2',1,1);
INSERT INTO batteries (id,code,name,cutoff,nominal,maxcharge,rechargable,cylindrical) VALUES (2,'IFR','Lithium Iron Phosphate','2','3.2','3.65',1,1);
INSERT INTO batteries (id,code,name,cutoff,nominal,maxcharge,rechargable,cylindrical) VALUES (3,'IMR','Lithium Manganese Oxide','2.5','3.9','4.2',1,1);
INSERT INTO batteries (id,code,name,cutoff,nominal,maxcharge,rechargable,cylindrical) VALUES (4,'NCR','Lithium Nickel Cobalt Aluminium Oxide','3.0','3.6','4.3',1,1);
INSERT INTO batteries (id,code,name,cutoff,nominal,maxcharge,rechargable,cylindrical) VALUES (5,'INR','Lithium Nickel Manganese Cobalt Oxide','2.5','3.6','4.2',1,1);
INSERT INTO batteries (id,code,name,cutoff,nominal,maxcharge,rechargable,cylindrical) VALUES (6,'CR','Lithium Manganese Dioxide','2','3',NULL,0,0);
INSERT INTO batteries (id,code,name,cutoff,nominal,maxcharge,rechargable,cylindrical) VALUES (7,'BR','Lithium Carbon Monofluoride','2','3',NULL,0,0);
INSERT INTO batteries (id,code,name,cutoff,nominal,maxcharge,rechargable,cylindrical) VALUES (8,'FR','Lithium Iron Disulfide','0.9','1.5','1.8',NULL,0,0);
INSERT INTO batteries (id,code,name,cutoff,nominal,maxcharge,rechargable,cylindrical) VALUES (9,'LTO','Lithium Titanate','1.6','2.3','2.8',1,0);

CREATE TABLE batterycodes (id INTEGER PRIMARY KEY AUTOINCREMENT, Code VARCHAR, BatteryID INTEGER);
INSERT INTO batterycodes (code,batteryid) values ('Li-Mn',6);
INSERT INTO batterycodes (code,batteryid) values ('Li-MnO2',6);
INSERT INTO batterycodes (code,batteryid) values ('LiCF',7);
INSERT INTO batterycodes (code,batteryid) values ('LiFeS2',8);
INSERT INTO batterycodes (code,batteryid) values ('LCO',1);
INSERT INTO batterycodes (code,batteryid) values ('LiCoO2',1);
INSERT INTO batterycodes (code,batteryid) values ('LiFePo4',2);
INSERT INTO batterycodes (code,batteryid) values ('LFP',2);
INSERT INTO batterycodes (code,batteryid) values ('LiFePo',2);
INSERT INTO batterycodes (code,batteryid) values ('LMO',3);
INSERT INTO batterycodes (code,batteryid) values ('LiMn',3);
INSERT INTO batterycodes (code,batteryid) values ('LiMn2O4',3);
INSERT INTO batterycodes (code,batteryid) values ('NCA',4);
INSERT INTO batterycodes (code,batteryid) values ('LiNiCoAlO2',4);
INSERT INTO batterycodes (code,batteryid) values ('NMC',5);
INSERT INTO batterycodes (code,batteryid) values ('NCM',5);

