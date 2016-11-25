SELECT album, song 
FROM musictbl 
WHERE location = "Camden" 
AND song NOT in (
    SELECT song 
    FROM musictbl 
    WHERE location = "Orleans"
)
GROUP BY album