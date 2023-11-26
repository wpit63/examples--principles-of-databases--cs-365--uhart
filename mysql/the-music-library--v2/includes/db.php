<?php

/**
 * Looks for a $value from an $attribute’s column in a $table, returning true if
 * found, false if not. For example, if a value named “stairway to heaven”
 * exists under an attribute called “name” within a table called “songs,” then
 *
 *    valueExistsInAttribute("stairway to heaven", "name", "songs")
 *
 * would return true.
 *
 * @param String $value     The query I’m interested in finding.
 * @param String $attribute The attribute under which I would like to locate $value.
 * @param String $table     The table containing the $attribute.
 *
 * @access public
 * @return Boolean return true upon finding a $value in the $attribute of $table.
 */
function valueExistsInAttribute($value, $attribute, $table) {
    try {
        include_once "config.php";

        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        $statement = $db -> prepare("SELECT $attribute FROM $table");
        $statement -> execute();

        $found = false;

        while (($row = $statement -> fetch())) {
            if ($value == $row[$attribute]) {
                $found = true;

                break;
            }
        }

        $statement = null;

        return $found;
    }
    catch(PDOException $error) {
        echo "<p class='highlight'>The function <code>valueExistsInAttribute</code> has generated an error:</p>" .
        "<pre>$error</pre>";

        exit;
    }
}

/**
 * Returns one $value — or the first, if more than one is retrieved — from a
 * $table if a $query should match a $pattern. For example, imagine you want the
 * album name from an album table whose artist ID is 2:
 *
 *    $album = select("album_name", "album", "artist_id", "2");
 *
 * @param String $value   The attribute I want to retrieve
 * @param String $table   The table in which the attribute resides
 * @param String $query   The query I want to match
 * @param String $pattern The pattern that the query should match
 *
 * @access public
 * @return string on success or Boolean on failure.
 */
function getValue($value, $table, $query, $pattern) {
    try {
        include_once "config.php";

        $db = new PDO("mysql:host=".DBHOST."; dbname=".DBNAME, DBUSER, DBPASS);

        $statement = $db -> prepare("SELECT $value FROM $table WHERE $query = :q");

        $statement -> execute(array('q' => $pattern));

        $row = $statement -> fetch();

        $statement = null;

        if ($row === false ) {
            $result = false;
        } else {
            $result = $row[$value];
        }

        return $result;
    }
    catch(PDOException $error) {
        echo "<p class='highlight'>The function <code>getValue</code> has generated an error:</p>" .
            "<pre>$error</pre>";

        exit;
    }
}

/**
 * Updates the $current_attribute in a $table to a $new_attribute based on
 * whether $query_attribute matches $pattern. For example, if you wanted to
 * update the album name of Warpaint’s Heads Up to HEADS UP, you would use this
 * function as follows:
 *
 *    update("album", "album_name", "HEADS UP", "album_name", "Heads Up");
 *
 * @param String $table             The table holding the attribute to update
 * @param String $current_attribute The current attribute that will be
 *                                    updated
 * @param String $new_attribute     The new attribute that will replace the
 *                                    current attribute
 * @param String $query_attribute   The attribute to be queried
 * @param String $pattern           The pattern the query attribute will need to
 *                                    match.
 *
 * @access public
 * @return none
 */
function updateAttribute($table, $current_attribute, $new_attribute, $query_attribute, $pattern) {
    try {
        include_once "config.php";

        $db = new PDO("mysql:host=".DBHOST."; dbname=".DBNAME,
            DBUSER,
            DBPASS);

        $statement = $db -> prepare(
            "UPDATE $table " .
            "SET $current_attribute = :new_attribute " .
            "WHERE $query_attribute = :pattern"
        );

        $statement -> execute(
            array('new_attribute' => $new_attribute, 'pattern' => $pattern )
        );

        $statement = null;
    }
    catch(PDOException $e) {
        echo "Error message generated by function <code>update</code>: $e";

        exit;
    }
}

/**
 * Deletes an entry from a $table where a $query matches $attribute. For
 * example, if I wanted to delete a user whose username was “guitarist” from a
 * table called user, I would use this function as follows:
 *
 *    delete("user", "username", "guitarist");
 *
 * @param String $table The table holding the query to delete
 * @param String $attribute The field whose query I want to match for deletion
 * @param String $query The entry I care to delete
 *
 * @access public
 * @return none
 */
function delete($table, $attribute, $query) {
    try {

        include_once "config.php";

        $db = new PDO("mysql:host=".DBHOST."; dbname=".DBNAME,
            DBUSER,
            DBPASS);

        $statement = $db -> prepare("DELETE FROM $table WHERE $attribute = :query");
        $statement -> execute(array('query' => $query));
        $statement = null;
    }
    catch(PDOException $error) {
        echo "<p class='highlight'>The function <code>delete</code> has generated an error:</p>" .
            "<pre>$error</pre>";

        exit;
    }
}

/**
 * Prints each $attribute associated with a $table. For example, if I wanted to
 * print every album name in an album database, I would use this function as
 * follows:
 *
 *    printAttributesFromTable("album_name", "album");
 *
 * @param $attribute  The attribute whos values I’d like to print
 * @param $table      The table to which the attribute belongs
 *
 * @access public
 * @return void
 */
function printAttributesFromTable($attribute, $table) {
    try {
        include_once "includes/config.php";

        $row = array();

        $db = new PDO(
            "mysql:host=" . DBHOST . ";dbname=" . DBNAME,
            DBUSER,
            DBPASS
        );

        $statement = $db -> prepare("SELECT $attribute FROM $table");
        $statement -> execute();

        while($row = $statement -> fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
            echo "<li>$row[0]</li>\n";
        }

        $statement = null;

    } catch(PDOException $e) {
        echo "<div>Error thrown in <code>getAttributeFromTable</code></div>";
        echo $e -> getMessage();

        exit;
    }
}
