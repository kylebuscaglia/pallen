# pallen
Project Allen PHP Repo

Pallen is the backend service required to consume a Twilio based webhook to intercept and process SMS text messages.


Step 1. Create an account on Twilio and follow the necessary steps to aquire a number.  
Step 2. On the dashboard, register Pallen as the webhook endpoint. Pallen expects a POST require to /callback.  
