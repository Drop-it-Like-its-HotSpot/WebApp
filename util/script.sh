DATABASE=mobile
USERNAME=gators
HOSTNAME=hotspotdb.cwboxzguz674.us-east-1.rds.amazonaws.com
export PGPASSWORD=hotspotuf

psql -h $HOSTNAME -U $USERNAME $DATABASE << EOF
select * from users
EOF
