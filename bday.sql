SELECT DATE_FORMAT(dob,'%M %d') AS dob, CONCAT(lname, ', ', fname) AS uname, organizations.org_name FROM contacts 
INNER JOIN organizations ON contacts.org_id=organizations.id
WHERE contacts.is_deleted=0 AND 
WEEK(dob) BETWEEN WEEK( CURDATE() )  AND  WEEK( DATE_ADD(CURDATE(), INTERVAL +21 DAY) )
ORDER BY dob