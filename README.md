# Budgeting Application

## Overview
This is a PHP-based budgeting application that helps users manage their finances by tracking incomes, expenses, and savings goals. Users can register, log in, and input their financial data, which is then calculated and presented to them for better budgeting.

## Features
- **User Registration & Login**: Secure user authentication system, allowing users to register and access their personal budgeting data.
- **Income Tracking**: Users can add and manage their income entries with descriptions, amounts, and dates.
- **Expense Tracking**: Similar to incomes, users can input expenses to keep track of their spending.
- **Savings Goals**: Users can set specific savings goals, defining a target amount and a deadline. The app automatically updates the progress towards each goal as users input new incomes or savings.
- **Calculations**: The application dynamically calculates and updates:
  - Total Income
  - Total Expenses
  - Net Balance (Income - Expenses)
  - Savings Progress (based on user-defined goals)

## How It Works
1. **Register & Log in**: Users sign up using their email and a password, then log in to access the app's dashboard.
2. **Dashboard**: The main dashboard allows users to input and track their incomes, expenses, and savings goals.
3. **Adding Data**: Users can easily add new income, expenses, and savings goals through the simple and intuitive interface. The application calculates and presents the data on the dashboard in real-time.
4. **Savings Goals Progress**: As users add more income, the progress towards each savings goal is updated, allowing users to track how close they are to reaching their targets.

## Technologies Used
- **Backend**: PHP with PDO for secure database interactions.
- **Database**: MySQL for storing user information, incomes, expenses, and savings goals.
- **Frontend**: HTML, CSS, and basic JavaScript for the user interface.
- **Hosting**: The app can be hosted on any platform that supports PHP and MySQL.

## Installation Instructions
1. Clone this repository:
   ```bash
   git clone https://github.com/subzero0008/budgeting_app.git
