

create database db_Courier;

use db_Courier;

CREATE TABLE tb_Admin
(
	admin_id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(100) /*NOT NULL*/,
	admin_password VARCHAR(250) /*NOT NULL*/,
	admin_address VARCHAR(250) /*NOT NULL*/,
	contact_num VARCHAR(100) /*NOT NULL*/,
	email_address VARCHAR(100) UNIQUE /*NOT NULL*/,
	join_date DATETIME /*NOT NULL*/ DEFAULT NOW(),
	updated_at DATETIME /*NOT NULL*/ DEFAULT NOW()
);

CREATE TABLE tb_Branch
(
  branch_id INT PRIMARY KEY AUTO_INCREMENT,
  branch_name VARCHAR(100) /*NOT NULL*/,
  branch_address VARCHAR(200) /*NOT NULL*/,
  branch_code VARCHAR(200) UNIQUE /*NOT NULL*/,
  branch_email_address VARCHAR(100) UNIQUE /*NOT NULL*/,
  branch_phone_no VARCHAR(100) /*NOT NULL*/,
  updated_by_admin INT, 
  FOREIGN KEY(updated_by_admin) REFERENCES tb_Admin(admin_id),
  inserted_at DATETIME /*NOT NULL*/ DEFAULT NOW(),
  updated_at DATETIME /*NOT NULL*/ DEFAULT NOW()
);

CREATE TABLE tb_Agent	
(
	agent_id INT PRIMARY KEY AUTO_INCREMENT,
	agent_name VARCHAR(100) /*NOT NULL*/,
	agent_password VARCHAR(250) /*NOT NULL*/,
	city VARCHAR(100) /*NOT NULL*/,
	contact_num VARCHAR(100) /*NOT NULL*/,
	email_address VARCHAR(100) UNIQUE /*NOT NULL*/,
    branch_location INT, 
	FOREIGN KEY(branch_location) REFERENCES tb_Branch(branch_id),
    updated_by_admin INT, 
	FOREIGN KEY(updated_by_admin) REFERENCES tb_Admin(admin_id),
	inserted_at DATETIME /*NOT NULL*/ DEFAULT NOW(),
	updated_at DATETIME /*NOT NULL*/ DEFAULT NOW()
);

CREATE TABLE tb_User
(
	user_id INT PRIMARY KEY AUTO_INCREMENT,
	user_name VARCHAR(100) /*NOT NULL*/,
	user_password VARCHAR(250) /*NOT NULL*/,
	user_address VARCHAR(250) /*NOT NULL*/,
	contact_num VARCHAR(100) /*NOT NULL*/,
	email_address VARCHAR(100) UNIQUE /*NOT NULL*/,
	updated_by_admin INT, 
	FOREIGN KEY(updated_by_admin) REFERENCES tb_Admin(admin_id),
	updated_by_agent INT, 
	FOREIGN KEY(updated_by_agent) REFERENCES tb_Agent(agent_id),
    updated_at DATETIME /*NOT NULL*/ DEFAULT NOW(),
	join_date DATETIME /*NOT NULL*/ DEFAULT NOW()
);

CREATE TABLE tb_Courier_Company
(
	courier_id INT PRIMARY KEY AUTO_INCREMENT,
	company_name VARCHAR(100) UNIQUE /*NOT NULL*/,
	contact_num VARCHAR(100) /*NOT NULL*/,
	email_address VARCHAR(100) UNIQUE /*NOT NULL*/,
	updated_by_admin INT, 
	FOREIGN KEY(updated_by_admin) REFERENCES tb_Admin(admin_id),
    inserted_at DATETIME /*NOT NULL*/ DEFAULT NOW(),
    updated_at DATETIME /*NOT NULL*/ DEFAULT NOW()
);

CREATE TABLE tb_Shipments
(
	shipment_id INT PRIMARY KEY AUTO_INCREMENT,
	sender_name VARCHAR(100) /*NOT NULL*/,
	sender_zip_code VARCHAR(100) UNIQUE /*NOT NULL*/,
	sender_address VARCHAR(100) /*NOT NULL*/,
	sender_phone_no VARCHAR(100) /*NOT NULL*/,
	sender_email_id VARCHAR(100) UNIQUE /*NOT NULL*/,
	receiver_name VARCHAR(100) /*NOT NULL*/,
	receiver_zip_code VARCHAR(100) UNIQUE /*NOT NULL*/,
	receiver_address VARCHAR(100) /*NOT NULL*/,
	receier_phone_no VARCHAR(100) /*NOT NULL*/,
	receiver_email_id VARCHAR(100) UNIQUE /*NOT NULL*/,
	bill_to VARCHAR(100) /*NOT NULL*/,
	no_of_parcel VARCHAR(56) /*NOT NULL*/,
	parcel_type VARCHAR(56) /*NOT NULL*/,
	delivery_status VARCHAR(100) /*NOT NULL*/,
	delivery_date DATETIME /*NOT NULL*/,
	order_date DATETIME /*NOT NULL*/ DEFAULT NOW(),
	shipping_cost DECIMAL(6,2) /*NOT NULL*/ DEFAULT 0,
	total_charge DECIMAL(7,2) /*NOT NULL*/,
	order_status VARCHAR(20) /*NOT NULL*/ DEFAULT 'waiting' CHECK (order_status IN ('waiting', 'confirmed', 'shipped', 'delivered', 'cancelled')),
	status_date DATETIME /*NOT NULL*/ DEFAULT NOW(),
	remarks VARCHAR(100), -- in case of cancellation admin can store why order was cancelled
	courier_com INT,
	FOREIGN KEY(courier_com) REFERENCES tb_Courier_Company (courier_id),
	updated_by_admin INT, 
	FOREIGN KEY(updated_by_admin) REFERENCES tb_Admin(admin_id),
	updated_by_agent INT, 
	FOREIGN KEY(updated_by_agent) REFERENCES tb_Agent(agent_id), 
    inserted_at DATETIME /*NOT NULL*/ DEFAULT NOW(),
	updated_at DATETIME /*NOT NULL*/ DEFAULT NOW()
);


-- relation b/w order and products table M-M
--CREATE TABLE tb_ORDER_DETAILS
--(
--	order_detail_id INT PRIMARY KEY IDENTITY(1,1),
--	shipment_id INT /*NOT NULL*/ FOREIGN KEY REFERENCES tb_shipments(shipment_id),
--	unit_price DECIMAL(7,2) /*NOT NULL*/ DEFAULT 0,
--	quantity INT /*NOT NULL*/
--);

INSERT INTO `tb_admin`(`name`, `admin_password`, `admin_address`, `contact_num`, `email_address`, `join_date`, `updated_at`)
 VALUES ('Ahmed Ali','ahmed','North Karachi','0325-6987418','ahmedali@gmail.com', CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP());

 INSERT INTO `tb_branch`(`branch_name`, `branch_address`, `branch_code`, `branch_email_address`, `branch_phone_no`, `updated_by_admin`, `inserted_at`, `updated_at`)
  VALUES ('North Karachi Branch','11-A North Karachi','1122','north123@gmail.com','0213-5487962', 1, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP());