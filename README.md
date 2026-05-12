
# Library_system

Please Note this common rules to avoid not found errors and name errors ! .

1.Database Connection: Always use only the variable $conn for all database interactions.
2.Database Integrity: Use the provided library_system database. Do not modify table structures.
3.To avoid "Table not found" errors, always use the exact singular table names and correct primary 

 book_id for the book table, member_id for the member table, user_id for the user table, category_id for the bookcategory table, borrow_id for the bookborrower table, and fine_id for the fine table.


All coding must follow the existing directory structure:

Admin Features: Must be placed inside /admin/
Example: admin/books.php, admin/members.php
Configuration: Database connection must be referenced using:
../config/db.php
Shared Components: Headers/footers must be referenced using:
../includes/
Assets: CSS must be linked using:
../assets/css/style.css
Use local Bootstrap files only
../assets/css/css/style.css


every admin file and access control files must include these in top of the coding

include '../includes/session_check.php'; // verify user logged in
include '../config/db.php'; // doing database connection

s

Each groupmemeber(devoloper) must implement the required validations for their feature:

ID Formats: Must use Regular Expressions (examples: U001, B001, M001, C001, BR001)
Passwords: Must be strictly more than 8 digits
Email: Must be validated for correct email format (example: sample@mymail.com)
Fines: Must be between 2 LKR and 500 LKR

Must work on their own branch .

