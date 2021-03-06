Laravel Loan EMI Rest Api

Version 7.3

========================================
run below command in your www or html file

Run git clone https://github.com/parasp2003/loanemi.git

Run composer update.

Run php artisan serve.

Go to link localhost:8000 OR 127.0.0.1:8000
===================================================
This app is a Loan API app that will give you an idea of the working of APIS in Laravel.
Database setup

Please open your localhost phpmyadmin / adminer, etc.

Navigate to folder SQL Schema File in this project and you will find a file named loanapi.sql  Import that SQL file in you DBs list
Request Format and type

If you are using POSTMAN. Please follow the below process

    Postman api collections folder Postman_collections 

    Set request type to POST
    Set your base request URL
    Select body and then select x-www-form-urlencoded
    Below are list of APIs with required fields

    find collection from Postman_collections folder and import in postman

List of APIs

Note: My local server was http://127.0.0.1:8000 You may change the settings as per you laravel environment

    http://127.0.0.1:8000/api/singup ( Pre Login )
    http://127.0.0.1:8000/api/login ( Pre Login )
    http://127.0.0.1:8000/api/applyloan ( Post Login )
    http://127.0.0.1:8000/api/approveloan ( Post Login )
    http://127.0.0.1:8000/api/payemi ( Post Login )
    http://127.0.0.1:8000/api/logout ( Post Login )

Application Flow

Note: You will receive JSON responses to every API request

 **** Step 1:****  You are a new user so visit /api/register to register first.
     Fields required for this request are name, email, password & confirm Password

     ( Pls note your password will be encrypted so kindly memorize it ) 
    Expected Response: {"success": true,    "data": {"name": "xyz"},"message": "xyz Signup successfully."}

  **Step 2 :**  You need to now login so visit /api/login. Fields required for this request are email & password
    Expect Response:{
    "success": true,
    "data": {
        "token": "eyJ0eXAiOiJKV1QiL...", 
    },
    "message": "login successfully."
     "name": "xyz user"
}

  **  Note: token, you will need this for every API request in headers
    Headers : 
    Authorization : Bearer eyJ0eXAiOiJKV1QiL... 
    Accept : Accept **

   **Step 3 :**  You may apply for a loan by visiting /api/applyloan Fields required for this request are  amount,duration
    Note : duration is in week 

    Expected Response:{"success":true,"data":{"loan_id":1},"message":"Loan apply successful! Please Approve loan using loan id"}

  **Step 4 :**  If you want to approve your loan you may head to /api/approveloan Fields required for this request are   loan_id

    Note: for loan_id ( Loan id which apply for loan approval)

    Expected Response: {    "success": true,    "data": {"LoanAmount": "$5000","Duration": "52 Weeks",
        "EMIAmount": "$97 Per Week"  },    "message": "Loan Approve successful!"}

  **Step 5:**   Now you need to pay EMIs you may visit /api/payemi Fields required for this request are loan_id, emi_amount

    Note: loan amount should be same as emi amount.

    Expect Response ( For every valid EMI Payment ): {
    "success": true,
    "data": {
        "LoanAmount": "$5000",
        "Duration": "52 Weeks",
        "EMIAmount": "$97 Per Week",
        "PaidAmount": "$679 Paid",
        "RemainingAmount": "$4321 Pending Amount"
    },
    "message": "Loan EMI Successfully Paid!"
}

 **Step 6 :** Logout user 

 	 Headers : 
    Authorization : Bearer eyJ0eXAiOiJKV1QiL... 
    Accept : Accept

    Expect Response   {"message": "Successfully logged out" }
