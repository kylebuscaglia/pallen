# pallen
Project Allen Laravel (PHP) Repo

Pallen is a laravel based backend service that can consume a Twilio based webhook to process an incoming SMS message and respond back.


Step 1. Create an account on Twilio and follow the necessary steps to aquire a number.  
Step 2. On the dashboard, register Pallen as the webhook endpoint. Pallen expects a POST require to /callback.  
