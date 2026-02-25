function MyAAC-Make-Plugin([string]$file, [string]$extraFiles = "") {
    $json = Get-Content "plugins/$file.json" | Out-String | ConvertFrom-Json
    $fileName = "myaac-$file-v$($json.version).zip";

    write-host "Making plugin: $($json.name) v$($json.version)"

    if ($extraFiles) {
        Compress-Archive plugins,$extraFiles -Force $fileName
    } else {
        Compress-Archive plugins -Force $fileName
    }

    write-host "Done! Created $fileName"

    Read-host -prompt "Press any key to exit"
}
