if service mysql status | grep -q "is running"; then
  echo "OK"
else
  service mysql restart
fi