<?php

$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $country = isset($_GET['country']) ? $_GET['country'] : '';
    $lookup = isset($_GET['lookup']) ? $_GET['lookup'] : 'countries';

    if ($lookup === 'cities') {
        
        $sql = "SELECT cities.name AS city_name, cities.district, cities.population 
                FROM cities 
                JOIN countries ON cities.country_code = countries.code 
                WHERE countries.name LIKE :country";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['country' => "%$country%"]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<thead>
                    <tr>
                        <th>City Name</th>
                        <th>District</th>
                        <th>Population</th>
                    </tr>
                  </thead>";
            echo "<tbody>";
            
            foreach ($results as $row) {
                echo "<tr>
                        <td>{$row['city_name']}</td>
                        <td>{$row['district']}</td>
                        <td>{$row['population']}</td>
                      </tr>";
            }
            
            echo "</tbody></table>";
        } else {
            echo "<p>No cities found.</p>";
        }
    } else {
        
        $sql = $country ? 
               "SELECT * FROM countries WHERE name LIKE :country" : 
               "SELECT * FROM countries";
        
        $stmt = $pdo->prepare($sql);
        
        if ($country) {
            $stmt->execute(['country' => "%$country%"]);
        } else {
            $stmt->execute();
        }

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<thead>
                    <tr>
                        <th>Country Name</th>
                        <th>Continent</th>
                        <th>Independence Year</th>
                        <th>Head of State</th>
                    </tr>
                  </thead>";
            echo "<tbody>";
            
            foreach ($results as $row) {
                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['continent']}</td>
                        <td>" . ($row['independence_year'] ?? 'N/A') . "</td>
                        <td>" . ($row['head_of_state'] ?? 'N/A') . "</td>
                      </tr>";
            }
            
            echo "</tbody></table>";
        } else {
            echo "<p>No results found.</p>";
        }
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
