function MyAAC-Make-Plugin([string]$file) {
    $json = Get-Content "plugins/$file.json" | Out-String | ConvertFrom-Json
    $fileName = "myaac-$file-v$($json.version).zip";

    write-host "Making plugin: $($json.name) v$($json.version)"
    Compress-Archive plugins -Force $fileName
    write-host "Done! Created $fileName"

    Read-host -prompt "Press any key to exit"
}
