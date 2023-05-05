<?php
include 'pdo_connect.php';
include 'headers.php';

$_POST['invoice_item_id'] = 6; // testi ID mikä poistetaan taulusta invoice_items

if (isset($_POST["invoice_item_id"])) {
    $invoice_item_id = $_POST["invoice_item_id"];
} else {
    echo "Ei löydetty laskua tällä ID:llä.";
    die();
}
$stmt = $pdo->prepare("DELETE FROM invoice_items WHERE InvoiceLineId = :invoice_item_id");
$stmt->execute(array(':invoice_item_id' => $invoice_item_id));

if ($stmt->rowCount() > 0) {
    echo "Lasku minkä ID =  $invoice_item_id poistettiin onnistuneesti.";
} else {
    echo "Ei löydetty laskua minkä ID = $invoice_item_id.";
}
?>

