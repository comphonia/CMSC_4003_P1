<?php
require "Models/utility_functions.php";

$password = "c4ca4238a0b923820dcc509a6f75849b";
$id =1;

$stmt =  "SELECT user_id, firstname, lastname, U.role_id, role_name as role
                                        FROM \"User\" U
                                        JOIN  Role R on U.role_id = R.role_id";

$qry = execute_sql_in_oracle($stmt);
$cursor = $qry["cursor"];

$result = execute_sql_in_oracle($stmt);

$data = [];
$cursor = $result["cursor"];

if ($result["flag"]) {
    while ($row = oci_fetch_assoc($cursor)){
            array_push($data,$row);
    }
    oci_free_statement($cursor);
}



var_dump(array_change_key_case_recursive($data));