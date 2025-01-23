# Web Application with CI/CD, Web Firewall, Route53, and Database

This repository demonstrates how to set up a web application using AWS services. The application is deployed with CI/CD pipelines, protected by a Web Application Firewall (WAF), and utilizes Route 53 for DNS management, with an RDS instance for database connectivity.
![Untitled Diagram](https://github.com/user-attachments/assets/b321c0e0-f60d-49c9-a2a4-01b6a5ed4bac)


# User Data for Dependencies installations for ubuntu:-

# [user-data for EC2]
```
#!/bin/bash
sudo apt update
sudo apt install -y ruby
sudo apt install -y wget
cd /home/ubuntu
wget https://aws-codedeploy-ap-south-1.s3.ap-south-1.amazonaws.com/latest/install
sudo chmod +x ./install
sudo ./install auto
sudo apt install -y python3-pip
sudo apt install -y python3.12-venv
cd ~/newenv
python3 -m venv newenv
source newenv/bin/activate
sudo pip install awscli
sudo apt install -y apache2
sudo systemctl restart codedeploy-agent.service

#Install PHP and MySQL
sudo apt update
sudo apt install -y php libapache2-mod-php php-mysql
sudo apt install -y mysql-server
```

## VPC Creation
I have created a **Custom VPC** with the following components:
- 2 public subnets
- 1 private subnet

This VPC setup ensures secure communication between resources while allowing external access where needed (for example, accessing the web application). 

### VPC Architecture
![vpc](https://github.com/user-attachments/assets/5cab3a02-668a-42fb-8422-b37e8e0c2287)

### Resource Map
![resource map](https://github.com/user-attachments/assets/bd388ba2-1922-4804-b10f-cb1a54b7135a)

## Subnets

- **Public Subnets**: Hosts the Bastion host (for accessing private resources).
- **Private Subnet**: Hosts the application EC2 instance.
![subnet](https://github.com/user-attachments/assets/9f731e00-2100-4f31-bdf3-9309b5f5dcb3)

## Route Tables

- **Public Route Table**: Allows internet access for resources in the public subnet.
![public route](https://github.com/user-attachments/assets/1ac909f7-a62b-476d-b41c-83e15df6f775)
![public route 2](https://github.com/user-attachments/assets/75dc7e45-3c83-440a-9405-2ea5a2d3a50c)

- **Private Route Table**: Routes traffic for private resources via NAT gateway for internet access.
![private route](https://github.com/user-attachments/assets/9abde4ce-cf33-437c-95e8-aa2682b3586c)
![private route 1](https://github.com/user-attachments/assets/b03a1176-0d1b-423c-ac53-a159ef1234ed)

## NAT Gateway
The NAT Gateway ensures that resources in the private subnet can access the internet for software updates and other necessary tasks, without exposing them directly to the internet.
![nat](https://github.com/user-attachments/assets/9cc6ebee-ea3f-4ffe-9ce9-5b13d2b36f1d)

## EC2 Instances

- **Public EC2 Instance**: This EC2 instance is placed in the public subnet and acts as a Bastion Host, allowing SSH access to the private EC2 instance for management and maintenance.
- **Private EC2 Instance**: This EC2 instance, which will host the web application, is placed in the private subnet for better security.

![ec2](https://github.com/user-attachments/assets/f7e1620f-24e0-4e07-911f-f9c900599b20)

## Auto Scaling Group

An **Auto Scaling Group** is configured to scale the EC2 instances automatically based on traffic demand. This ensures that the application can handle varying levels of load.
![ASG](https://github.com/user-attachments/assets/4c1bf4b9-ec23-4ef0-a53f-adb886841ee6)

## Application Load Balancer (ALB)

The **Application Load Balancer (ALB)** is used for distributing incoming traffic to multiple EC2 instances, ensuring high availability and reliability for the application.
![ALB](https://github.com/user-attachments/assets/88836d93-bc9b-4f4b-83ae-fbe9ae85bc48)

## RDS Instance (Database)

An **RDS instance** (MySQL) is used for database storage, and it is connected to the EC2 instance using PHP for handling application data.

![rds](https://github.com/user-attachments/assets/4490283c-c39e-47f1-9966-53759963b5ee)

## CI/CD with CodePipeline and CodeDeploy

The **CI/CD pipeline** is set up using **CodePipeline** and **CodeDeploy**:

1. **CodePipeline** automatically pulls the source code from GitHub.
2. The code is then passed to **CodeDeploy**, which deploys it to the EC2 instances.

### CodePipeline Setup

![pipeline](https://github.com/user-attachments/assets/138fe064-c76e-4bad-8c4b-a2f9f4add2d7)

### Deployment Process

- **Source**: CodePipeline fetches the source code from GitHub.
- **Build**: The code is built (if necessary).
- **Deploy**: The code is deployed to the EC2 instances via CodeDeploy.

![pipeline2](https://github.com/user-attachments/assets/5799d1b8-8b45-4b11-a281-4ba16413dd65)

### Successful Deployment

Once the deployment completes, the web application should be accessible via the ALB.
![deployment1](https://github.com/user-attachments/assets/1ead3341-dbe7-4dec-8335-ecad7a8ea93a)
![success1](https://github.com/user-attachments/assets/2bec71ac-9fc8-4397-b03d-2816d9d3d3e7)

## Testing CI/CD

To test the CI/CD pipeline, I made a code update in my GitHub repository. The pipeline automatically detected the change and deployed the updated application.

1. **Adding a new line "echo New Update"**:
   ![new update](https://github.com/user-attachments/assets/ef8c8fcd-a486-471a-a7b3-31a6a2dac072)

2. **Committing the change**:
   ![new update2](https://github.com/user-attachments/assets/14fbfe5b-51a8-4456-ac0f-65cc4cdf98e9)

3. **CodePipeline detects the change and triggers deployment**:
   ![new update3](https://github.com/user-attachments/assets/852045cf-a40e-42ed-b4fc-05cf63be24fd)

4. **Deployment successful**:
   ![new update4](https://github.com/user-attachments/assets/365b1d8b-733c-4cd5-9d7f-f08e75301f75)

   ![new update5](https://github.com/user-attachments/assets/b7134e02-2a15-42fd-baec-435435c154c9)

## Web Application Firewall (WAF)

The **Web Application Firewall (WAF)** protects the application from common web exploits.

1. **WAF Testing**: I tested the WAF by sending random traffic. It successfully blocked suspicious traffic.
   ![waf allowed](https://github.com/user-attachments/assets/54abf52b-fb58-4956-b882-c075ca8fc280)

2. **WAF Visualization**: WAF provides visualization of traffic patterns and blocked events.
   ![waf grahp](https://github.com/user-attachments/assets/94119f21-1cfa-4cec-baae-fa311be50355)
   ![device waf](https://github.com/user-attachments/assets/e42505a3-a111-4a91-990f-cc74d480f128)


3. **Testing WAF with VPN Traffic from Different Countries**:

   ![country](https://github.com/user-attachments/assets/a089fe74-7b8b-4f51-9fae-101f8e861c18)

## Route 53

I have used **Route 53** for DNS management. Although I don't have a domain name at the moment, I demonstrate how to create a hosted zone and configure DNS records.

1. **Create a Hosted Zone**: You can use your domain name or a random name if you don't have one.
   ![hosted zone](https://github.com/user-attachments/assets/8cdf5292-5212-4d1f-8639-03fd46faad42)

2. **Add a DNS Record**: This record will point your domain name to the web application load balancer (ALB).
   ![hosted zone record](https://github.com/user-attachments/assets/bc7fd050-3f66-4309-80c2-89abb58c6e67)

   **Important**: Copy the Route 53 name server (NS) records and paste them into the name server settings of the domain registrar where you purchased your domain.

## Additional Information

I have documented the challenges I encountered during the integration of the CI/CD pipeline. Please refer to the `problem` directory for detailed solutions.
