### Features / Protections
- Login (protected against brute force/dictionary attacks)
- Sign Up
- password reset (done in a secure and friendly manner)
- Email Validation (ensure user has access to the email they used to make the account)
- Evaluation Request upload page
- Evaluations List Display Admin page 
- CSRF protection for all features/forms
- Account Deletion 
- All features are protected from SQL Injection using PHP prepared statements
- XSS protection (see video for how to impliment when adding your own pages with untrusted data on them)
- All passwords are hashed so that even with access to the database attackers could not obtain users passwords (passwords are hashed and salted)


### Hot to use
1. Download and install either MAMP/XAMPP (alternatively individually download php, mysql, and an apache server if you know what you are doing)
2. Ensure you are using an up to date version of PHP. I tested with version 8 but 7 should work however I have not tested it myself.
3. Copy and paste all files into the public directory
4. Modify config.php to match your environment and use case (may have to modify other files as well)
5. Start mysql and apache server services
6. Visit your website and test 
