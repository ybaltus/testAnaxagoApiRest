IP=$1
HOSTNAME=$2
HOSTS_LINE="$IP\t$HOSTNAME"

if [ -n "$(grep $HOSTNAME /etc/hosts)" ]
then
    echo "$HOSTNAME already exists : $(grep $HOSTNAME $ETC_HOSTS)"
else
    echo "Adding $HOSTNAME to your $ETC_HOSTS";
    sudo -- sh -c -e "echo '$HOSTS_LINE' >> /etc/hosts";

    if [ -n "$(grep $HOSTNAME /etc/hosts)" ]
        then
            echo "$HOSTNAME was added succesfully \n $(grep $HOSTNAME /etc/hosts)";
        else
            echo "Failed to Add $HOSTNAME, Try again!";
    fi
fi