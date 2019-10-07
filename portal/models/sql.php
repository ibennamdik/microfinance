global $mysqli;
            $stmt = $mysqli->prepare("");
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();



global $mysqli;
			$stmt = $mysqli->prepare("");
			$stmt->execute();
			$result = $stmt->get_result();
			while ($row = $result->fetch_assoc()) {


global $mysqli;
                $stmt = $mysqli->prepare("");
                $stmt->execute();
                if($stmt->num_rows > 0)
                {

if ($mysqli->query($sql) === TRUE) {
                          echo "New record created successfully";
                      } else {
                          echo "Error: " . $mysqli->error;
                      }
                      $mysqli->close();