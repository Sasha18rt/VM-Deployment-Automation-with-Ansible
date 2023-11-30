
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css"> 
    <title>Fill Patient Card</title>
</head>
<body>
    <div class="container">
        <div class="row d-flex align-items-center justify-content-center" style="min-height: 100vh;"> 
            <div class="col-10 mx-auto registration-container">
                <h1>Fill Patient Card</h1>
                <form action="process_fill_patient_card.php" method="post">
                    <div class="mb-3">
                        <label for="p_id">Patient ID:</label>
                        <input type="text" class="form-control" name="p_id" id="p_id" placeholder="Enter Patient ID">
                    </div>
                    <div class="mb-3">
                        <label for="allergies">Allergies:</label>
                        <textarea class="form-control" name="allergies" id="allergies" placeholder="Enter Allergies"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="current_med">Current Medications:</label>
                        <textarea class="form-control" name="current_med" id="current_med" placeholder="Enter Current Medications"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="medical_history">Medical History:</label>
                        <textarea class="form-control" name="medical_history" id="medical_history" placeholder="Enter Medical History"></textarea>
                    </div>
                    <button class="btn btn-success" type="submit">Fill Patient Card</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
