## Create Database
DROP DATABASE if exists clinicRecord;
CREATE DATABASE clinicRecord;
USE clinicRecord;

## Create Personal Information Record table
CREATE TABLE PersonalInformation (
  StudentIDNumber bigint(20) NOT NULL,
  StudentImage longblob default null,
  Status varchar(255) NOT NULL,
  StudentCategory varchar(255) NOT NULL,
  Course varchar(255) NOT NULL,
  Year bigint(20) NOT NULL,
  Section varchar(255) NOT NULL,
  Lastname varchar(255) NOT NULL,
  Firstname varchar(255) NOT NULL,
  Middlename varchar(255) NOT NULL,
  Extension varchar(255) NOT NULL,
  Age bigint(20) NOT NULL,
  Birthdate varchar(255) NOT NULL,
  Sex varchar(255) NOT NULL,
  Address varchar(255) NOT NULL,
  StudentContactNumber bigint(20) NOT NULL,
  GuardianParent varchar(255) NOT NULL,
  GPCategory varchar(255) NOT NULL,
  ContactPerson varchar(255) NOT NULL,
  PGContactNumber bigint(20) NOT NULL,
  
  PRIMARY KEY  (StudentIDNumber)
);

## Create Medical Information Record table
CREATE TABLE MedicalInformation (
  Num bigint(20) unsigned ZEROFILL auto_increment,
  StudentIDNumber1 bigint(20) NOT NULL,
  Date varchar(255) NOT NULL,
  StaffIDNumber bigint(20) NOT NULL,
  StaffLastname varchar(255) NOT NULL,
  StaffFirstname varchar(255) NOT NULL,
  StaffMiddlename varchar(255) NOT NULL,
  StaffExtension varchar(255) NOT NULL,
  LMP varchar(255) NOT NULL,
  Pregnancy varchar(255) NOT NULL,
  Allergies varchar(255) NOT NULL,
  Surgeries varchar(255) NOT NULL,
  Injuries varchar(255) NOT NULL,
  Illness varchar(255) NOT NULL,
  SchoolYear varchar(255) NOT NULL,
  Height bigint(20) NOT NULL,
  Weight bigint(20) NOT NULL,
  BMI varchar(255) NOT NULL,
  BloodPressure bigint(20) NOT NULL,
  Temperature bigint(20) NOT NULL,
  PulseRate bigint(20) NOT NULL,
  VisionWithoutGlassesOD varchar(255) NOT NULL,
  VisionWithoutGlassesOS varchar(255) NOT NULL,
  VisionWithGlassesOD varchar(255) NOT NULL,
  VisionWithGlassesOS varchar(255) NOT NULL,
  HearingDistance bigint(20) NOT NULL,
  Speech varchar(255) NOT NULL,
  Eyes varchar(255) NOT NULL,
  Ears varchar(255) NOT NULL,
  Nose varchar(255) NOT NULL,
  Head varchar(255) NOT NULL,
  Abdomen varchar(255) NOT NULL,
  GenitoUrinary varchar(255) NOT NULL,
  LymphGlands varchar(255) NOT NULL,
  Skin varchar(255) NOT NULL,
  Extremities varchar(255) NOT NULL,
  Deformities varchar(255) NOT NULL,
  CavityAndThroat varchar(255) NOT NULL,
  Lungs varchar(255) NOT NULL,
  Heart varchar(255) NOT NULL,
  Breast varchar(255) NOT NULL,
  RadiologicExams varchar(255) NOT NULL,
  BloodAnalysis varchar(255) NOT NULL,
  Urinalysis varchar(255) NOT NULL,
  Fecalysis varchar(255) NOT NULL,
  PregnancyTest varchar(255) NOT NULL,
  HBSAg varchar(255) NOT NULL,
  Remarks text NOT NULL,
  Recommendation text NOT NULL,

  PRIMARY KEY  (Num)
);

## Create Useraccounts table
CREATE TABLE USERACCOUNTS (
  /*Num bigint(20) unsigned ZEROFILL auto_increment,*/
  StaffIDNum int (20) NOT NULL,
  Email varchar(255) NOT NULL,
  Username varchar(255) NOT NULL,
  Password varchar(255) NOT NULL,
  Lastname varchar(255) NOT NULL,
  Firstname varchar(255) NOT NULL,
  Middlename varchar(255) NOT NULL,
  Extension varchar(255) NOT NULL,
  Position varchar(255) NOT NULL,
  Rank varchar(255) NOT NULL,
  /*AccessLevel varchar(255) NOT NULL,*/
  ContactNum varchar(255) NOT NULL,
  PRIMARY KEY  (StaffIDNum)
);

## Create DEFAULT account for admin
INSERT INTO useraccounts (Username, Password, FirstName,MiddleName,LastName,Position) VALUES ('admin', '$2y$10$QDiyEIdPoP.VMyiBuAVZnOsJ1q5rBvkSxyeeG/bbqmwohohI65.hi', 'john','l','doe','doctor');