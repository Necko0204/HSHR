<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Salary Agreement Contract</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

  <!-- jsPDF CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <!-- html2canvas CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

  <style>
    #contract {
        font-family: Arial, sans-serif;
        font-size: 12pt;
        color: #000;
        width: 100%;
        max-width: 800px;
        margin: auto;
        padding: 20px;
        background: #fff;
        line-height: 1.7;
        page-break-inside: avoid;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        #contract, #contract * {
            visibility: visible;
        }
        #contract {
            position: absolute;
            left: 0;
            top: 0;
        }
        .header h1::after {
            display: block; /* Ensure the line below the header is visible */
        }
    }
    /* General Styles */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #dfe9f3, #ffffff);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .contract-container {
        background: #ffffff !important;
        color: #000 !important;
        padding: 50px 60px;
        max-width: 800px;
        width: 100%;
        margin: 0 auto;
        box-shadow: none !important;
        font-family: Arial, sans-serif;
    }


    @keyframes fadeIn {
      0% {
        opacity: 0;
        transform: translateY(40px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .header {
      text-align: center;
      margin-bottom: 40px;
    }

    .header h1 {
      font-family: 'Poppins', serif;
      font-size: 40px;
      color: #2c3e50;
      letter-spacing: 1px;
      position: relative;
    }

    .header h1::after {
      content: '';
      width: 600px;
      height: 4px;
      background: linear-gradient(to right, #74ebd5, #9face6);
      display: block;
      margin: 10px auto 0;
      border-radius: 2px;
    }

    .content {
      font-size: 16px;
      color: #333;
      line-height: 1.5;
    }

    .content p,
    .content ul {
      margin-bottom: 20px;
    }

    .content ul {
      padding-left: 25px;
    }

    .content ul li {
      margin-bottom: 12px;
    }

    .highlight {
      color: #1abc9c;
      font-weight: 600;
    }

    .signature-container {
    display: flex;
    justify-content: space-between;
    margin-top: 50px;
    gap: 40px;
    }

    .signature {
        width: 45%;
        text-align: center;
    }

    .signature hr {
        border: none;
        border-top: 2px solid #123456;
        margin-top: 30px;
    }


    .footer {
      text-align: center;
      margin-top: 60px;
      font-size: 14px;
      color: #888;
    }

    .footer a {
      color: #1abc9c;
      text-decoration: none;
    }

    .footer a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .contract-container {
        padding: 30px 20px;
      }

      .signature {
        flex-direction: column;
        gap: 40px;
      }

      .signature div {
        width: 100%;
      }
    }

    /* Button styles */
    .print-button, .download-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #1abc9c;
      color: white;
      border: none;
      border-radius: 5px;
      padding: 10px 15px;
      font-size: 14px;
      cursor: pointer;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: background-color 0.3s ease;
      z-index: 1000;
      margin-left: 10px;
    }

    .print-button:hover,
    .download-button:hover {
      background-color: #16a085;
    }

    .download-button {
      right: 120px;
    }
  </style>
</head>
<body>
  <div class="contract-container" id="contract">
    <div class="header">
      <h1>Salary Agreement Contract</h1>
    </div>
    <div class="content">
      <p>This <span class="highlight">Salary Agreement Contract</span> is entered into on <span class="highlight">[Date]</span> by and between:</p>
      <ul>
        <li><strong>Employer:</strong> <span class="highlight">[Employer Name]</span></li>
        <li><strong>Employee:</strong> <span class="highlight">[Employee Name]</span></li>
      </ul>

      <p>The employer agrees to compensate the employee as follows:</p>
      <ul>
        <li><strong>Salary:</strong> <span class="highlight">[Salary Amount]</span> per <span class="highlight">[Time Period]</span></li>
        <li><strong>Payment Method:</strong> Direct deposit to the employee's designated bank account.</li>
        <li><strong>Benefits:</strong> Health insurance, paid time off, retirement contributions, and performance bonuses.</li>
      </ul>

      <p>The employee agrees to:</p>
      <ul>
        <li>Perform their duties and responsibilities as outlined in the job description.</li>
        <li>Adhere to company policies, including confidentiality and non-compete agreements.</li>
        <li>Provide a 30-day notice period in case of resignation.</li>
      </ul>

      <p>Additional terms:</p>
      <ul>
        <li>Performance reviews will be conducted annually to assess eligibility for raises or promotions.</li>
        <li>Termination of this contract by the employer requires a valid reason and a 30-day notice period.</li>
        <li>Disputes arising from this contract will be resolved through arbitration as per company policy.</li>
      </ul>
    </div>


    <div class="signature-container">
    <div class="signature">
        <p>Employer Signature</p>
        <hr>
    </div>
    <div class="signature">
        <p>Employee Signature</p>
        <hr>
    </div>
</div>

    <div class="footer">
      <p>For any queries, please contact HR at <a href="mailto:hr@example.com">hr@example.com</a></p>
    </div>
  </div>

  <!-- Buttons -->
  <button class="print-button" onclick="printPage()">Print Page</button>
  <button class="download-button" onclick="downloadPDF()">Download PDF</button>

    <!-- html2pdf library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
    function printPage() {
        window.print();
    }

function downloadPDF() {
    const element = document.getElementById("contract");

    // Clone the element to avoid rendering issues
    const clone = element.cloneNode(true);
    clone.style.background = "#fff"; // Solid background
    clone.style.color = "#000"; // Ensure text visibility

    // Append the clone to the body temporarily to ensure proper rendering
    document.body.appendChild(clone);

    const opt = {
        filename:     'Salary_Agreement_Contract.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, backgroundColor: "#ffffff" },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(clone).save().then(() => {
        // Remove the temporary clone after saving
        document.body.removeChild(clone);
    });
}
</script>

</body>
</html>
