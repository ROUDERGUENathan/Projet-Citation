<?php
$brevoCleApi = '';

function envoyerEmail($destinataire, $sujet, $contenuHtml) {
    global $brevoCleApi;

    $donnees = [
        'sender' => ['name' => 'Dictionnaire de citations', 'email' => 'roudergue.nathan@gmail.com'],
        'to' => [['email' => $destinataire]],
        'subject' => $sujet,
        'htmlContent' => $contenuHtml,
    ];

    $ch = curl_init('https://api.brevo.com/v3/smtp/email');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($donnees));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept: application/json',
        'api-key: ' . $brevoCleApi,
        'content-type: application/json',
    ]);
    curl_exec($ch);
    $succes = curl_getinfo($ch, CURLINFO_HTTP_CODE) === 201;
    curl_close($ch);

    return $succes;
}
