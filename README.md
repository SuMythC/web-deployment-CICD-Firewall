# Web Application with CI/CD, Web Firewall, Route53, and Database

This repository demonstrates how to set up a web application using AWS services. The application is deployed with CI/CD pipelines, protected by a Web Application Firewall (WAF), and utilizes Route 53 for DNS management, with an RDS instance for database connectivity.

![Architecture Diagram](https://github.com/user-attachments/assets/1ebe0f20-3f84-42bc-ae96-ba683035dd18)

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
![VPC](https://github.com/user-attachments/assets/0ab6b3cb-8dd3-4a02-a5b1-4490e6630a01)

### Resource Map
![Resource Map](https://github.com/user-attachments/assets/d6bbbfc4-8202-4b76-8b4d-2c41a1afcd7b)

## Subnets

- **Public Subnets**: Hosts the Bastion host (for accessing private resources).
- **Private Subnet**: Hosts the application EC2 instance.

![Subnets](https://github.com/user-attachments/assets/f28dfd26-e5ff-49fa-a782-86f8442e2030)

## Route Tables

- **Public Route Table**: Allows internet access for resources in the public subnet.

![Public Route Table 1](https://github.com/user-attachments/assets/043f7f45-e397-4862-9a28-6b3700236768)
![Public Route Table 2](https://github.com/user-attachments/assets/dd0bcef4-06cf-4f23-933e-7846a7102fb1)

- **Private Route Table**: Routes traffic for private resources via NAT gateway for internet access.

![Private Route Table 1](https://github.com/user-attachments/assets/01ee85f9-fbdd-4bcf-a443-6d22c28d0239)
![Private Route Table 2](https://github.com/user-attachments/assets/af6a4867-5f6c-4418-bee1-0eba2603787e)

## NAT Gateway
The NAT Gateway ensures that resources in the private subnet can access the internet for software updates and other necessary tasks, without exposing them directly to the internet.

![NAT Gateway](https://github.com/user-attachments/assets/9f44fac5-ac6c-44ca-bbd1-6638821cae02)

## EC2 Instances

- **Public EC2 Instance**: This EC2 instance is placed in the public subnet and acts as a Bastion Host, allowing SSH access to the private EC2 instance for management and maintenance.
- **Private EC2 Instance**: This EC2 instance, which will host the web application, is placed in the private subnet for better security.

![EC2 Instances](https://github.com/user-attachments/assets/f7e2fda6-b207-46f9-8efc-f9cd4002a8c9)

## Auto Scaling Group

An **Auto Scaling Group** is configured to scale the EC2 instances automatically based on traffic demand. This ensures that the application can handle varying levels of load.

![Auto Scaling Group](https://github.com/user-attachments/assets/9b5c43ee-729c-4d2c-9bc0-65347d069e80)

## Application Load Balancer (ALB)

The **Application Load Balancer (ALB)** is used for distributing incoming traffic to multiple EC2 instances, ensuring high availability and reliability for the application.

![ALB](https://github.com/user-attachments/assets/a158fd56-5295-4922-add6-5820cada9b20)

## RDS Instance (Database)

An **RDS instance** (MySQL) is used for database storage, and it is connected to the EC2 instance using PHP for handling application data.

![RDS Instance](https://github.com/user-attachments/assets/83d6c255-1065-40e0-8999-a8da6c8f3efe)

## CI/CD with CodePipeline and CodeDeploy

The **CI/CD pipeline** is set up using **CodePipeline** and **CodeDeploy**:

1. **CodePipeline** automatically pulls the source code from GitHub.
2. The code is then passed to **CodeDeploy**, which deploys it to the EC2 instances.

### CodePipeline Setup

![CodePipeline](https://github.com/user-attachments/assets/9d4b5e82-1673-4b60-858c-120fe684982e)

### Deployment Process

- **Source**: CodePipeline fetches the source code from GitHub.
- **Build**: The code is built (if necessary).
- **Deploy**: The code is deployed to the EC2 instances via CodeDeploy.

![Pipeline Deployment](https://github.com/user-attachments/assets/cf129931-e460-4923-9517-da0948c8dc56)

### Successful Deployment

Once the deployment completes, the web application should be accessible via the ALB.

![Deployment Successful](https://github.com/user-attachments/assets/7751c852-3c4a-4a56-8f4a-9372ba7cbef5)
![Success](https://github.com/user-attachments/assets/8cec54a7-f7cf-4daf-a307-c560e368b8c0)

## Testing CI/CD

To test the CI/CD pipeline, I made a code update in my GitHub repository. The pipeline automatically detected the change and deployed the updated application.

1. **Adding a new line "echo New Update"**:

   ![New Update](https://github.com/user-attachments/assets/0be81e33-6144-4396-8efe-d84999801d5a)

2. **Committing the change**:

   ![Committing the Change](https://github.com/user-attachments/assets/6f453327-e74e-470b-b96c-77aeb7e7a681)

3. **CodePipeline detects the change and triggers deployment**:

   ![CodePipeline Triggered](https://github.com/user-attachments/assets/abd6ad42-3d6b-48ec-ba7d-c4f2a83b7fab)

4. **Deployment successful**:

   ![New Update Successful](https://github.com/user-attachments/assets/e47e9020-0f68-4c0e-a5d3-a07928cab320)
   ![Success Update](https://github.com/user-attachments/assets/12058cd4-c3c4-4881-9184-58b62bbb9438)

## Web Application Firewall (WAF)

The **Web Application Firewall (WAF)** protects the application from common web exploits.

1. **WAF Testing**: I tested the WAF by sending random traffic. It successfully blocked suspicious traffic.

   ![WAF Blocked Traffic](https://github.com/user-attachments/assets/5a7cd72c-bc48-46af-8dc7-348f972ed88b)

2. **WAF Visualization**: WAF provides visualization of traffic patterns and blocked events.

   ![WAF Graph](https://github.com/user-attachments/assets/f5a5dbe6-6dc3-40f0-badd-824b0a368435)
   ![Device WAF](https://github.com/user-attachments/assets/905afe52-c9bc-45ec-80fc-3073cd9991a1)

3. **Testing WAF with VPN Traffic from Different Countries**:

   ![VPN Country Traffic](https://github.com/user-attachments/assets/d684c017-0b56-4740-97e8-365b1bfb6e96)

## Route 53

I have used **Route 53** for DNS management. Although I don't have a domain name at the moment, I demonstrate how to create a hosted zone and configure DNS records.

1. **Create a Hosted Zone**: You can use your domain name or a random name if you don't have one.

   ![Hosted Zone](https://github.com/user-attachments/assets/f21c5ca6-b8d4-44e9-af07-b8cdae822800)

2. **Add a DNS Record**: This record will point your domain name to the web application load balancer (ALB).

   ![DNS Record](https://github.com/user-attachments/assets/9c9ea694-ad90-4f82-a69e-5626a9602c55)

   **Important**: Copy the Route 53 name server (NS) records and paste them into the name server settings of the domain registrar where you purchased your domain.

## Additional Information

I have documented the challenges I encountered during the integration of the CI/CD pipeline. Please refer to the `problem` directory for detailed solutions.
