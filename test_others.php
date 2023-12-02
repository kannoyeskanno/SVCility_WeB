<html>
        <head>
            <title>SVCility</title>
        </head>
        <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 25px; border: solid;'>
            <div class='container' style='border-radius:40px 40px 0px 0px; height: 80vh; max-width: 55%; margin: 20px auto; padding: 0; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>
              <div class='logo' style='text-align: center; margin-top: 5px; border: solid; padding: 10px;background: url("https://products.ls.graphics/paaatterns/images/009.png"); background-size: cover; border-radius:40px 40px 0px 10px;'>
                    <div class='image-container' style='display: inline-block; margin-top: 5px; overflow: hidden; border-radius: 50%;'>
                        <div class='image' style='width: 80px; height: 80px; background-size: cover; background-position: center; background-image: url(resources/logo/san_vicente_logo.png); background-color: black;'></div>
                    </div>
                
                </div>
                <div class='message' style='background-color: #f8f8ff;'>
                  <div style='margin: 20px auto; background-color: #f8f8ff; padding-left: 40px; padding-right:40px;'>
                      <div style='margin: 20px auto;  text-align: center;'>
                      <h2 style='color: #666666; text-decoration: underline;'><strong>" . htmlspecialchars($subject) . "</strong></h2>

                      </div>
                      <p style='color: #666666;'><strong>Purpose:</strong> " . htmlspecialchars($purpose) . "</p>
                      <p style='color: #666666;'><strong>Date:</strong> " . htmlspecialchars($datepicker) . "</p>
            

                </div>
                <div style='padding-left: 50px; padding-right: 50px; text-align: center; margin-top: 70px;'>
                  <a href='" . htmlspecialchars($link) . "' class='button' style='display: inline-block; padding: 12px 20px; font-size: 16px; font-weight: 500; letter-spacing: .25px; margin-bottom: 8px; text-decoration: none; text-transform: none; border-radius: 8px; background-color: #007bff; color: #fff; border: 1px solid #007bff; text-align: center;'>View Request</a>
                </div>
            </div>
        </body>
        </html>




<!-- <div class='image-container' style='display: inline-block; overflow: hidden;'>
      <div class='system-logo' style='width: 130px; height: 100px; background-size: cover; background-position: center; background-image: url(resources/logo/svcility_logo.png);'></div>
  </div> -->