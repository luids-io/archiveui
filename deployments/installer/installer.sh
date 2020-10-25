#!/bin/bash

## Configuration variables. 
NAME="archiveui"
RELEASE="RELEASE"

## Web application settings
ADMIN_NAME=${ADMIN_NAME:-Administrator}
ADMIN_USERNAME=${ADMIN_USERNAME:-admin}
ADMIN_PASSWORD=${ADMIN_PASSWORD:-admin}
ARCHIVE_HOST=${ARCHIVE_HOST:-localhost}
ARCHIVE_PORT=${ARCHIVE_PORT:-27017}
ARCHIVE_DATABASE=${ARCHIVE_DATABASE:-luidsdb}
ARCHIVE_USERNAME="$ARCHIVE_USERNAME"
ARCHIVE_PASSWORD="$ARCHIVE_PASSWORD"

## Web
WWW_DIR=/var/www
WWW_USER=www-data
APACHE_DIR=/etc/apache2

## Download
DOWNLOAD_BASE="https://github.com/luids-io/${NAME}/releases/download"
DOWNLOAD_URI="${DOWNLOAD_BASE}/${RELEASE}/${NAME}_${RELEASE}.tgz"

## Dependencies
DEPS="libapache2-mod-php7.4 php7.4-cli php7.4-gd php7.4-mbstring"
DEPS="$DEPS php7.4-xml php7.4-zip php7.4-sqlite3 php-mongodb"

##

die() { echo "error: $@" 1>&2 ; exit 1 ; }

## some checks
for dep in "wget" "mktemp" "getent" "apt-get" ; do
	which $dep >/dev/null || die "$dep is required!"
done
[[ $EUID -eq 0 ]] || die "This script must be run as root"

## options command line
OPT_UNATTEND=0
while [ -n "$1" ]; do
	case "$1" in
		-u) OPT_UNATTEND=1 ;;
		-h) echo -e "Options:\n\t [-u] unattend\n"
		    exit 0 ;; 
 		*) die "Option $1 not recognized" ;; 
	esac
	shift
done

echo
echo "======================"
echo "- luIDS installer:"
echo "   ${NAME} ${RELEASE}"
echo "======================"
echo

show_actions() {
	echo "Warning! This script will commit the following changes to your system:"
	echo ". Install dependencies using apt-get"
	echo ". Download and install web application in '${WWW_DIR}/${NAME}'"
	echo ". Initialize web application"
	echo ". Enable apache2 required modules and create alias '/${NAME}'"
	echo ""
}

if [ $OPT_UNATTEND -eq 0 ]; then
	show_actions
	read -p "Are you sure? (y/n) " -n 1 -r
	echo
	echo
	if [[ ! $REPLY =~ ^[Yy]$ ]]
	then
		die "canceled"
	fi
fi

TMP_DIR=$(mktemp -d -t ins-XXXXXX) || die "couldn't create temp"
LOG_FILE=${TMP_DIR}/installer.log

log() { echo `date +%y%m%d%H%M%S`": $@" >>$LOG_FILE ; }
step() { echo -n "* $@..." ; log "STEP: $@" ; }
step_ok() { echo " OK" ; }
step_err() { echo " ERROR" ; }
user_exists() { getent passwd $1>/dev/null ; }
group_exists() { getent group $1>/dev/null ; }

## do functions
do_download() {
	[ $# -eq 2 ] || die "${FUNCNAME}: unexpected number of params"
	local url="$1"
	local filename="$2"

	local dst="${TMP_DIR}/${filename}"
	rm -f $dst
	log "downloading $url"
	echo "$url" | grep -q "^\(http\|ftp\)"
	if [ $? -eq 0 ]; then
		wget "$url" -O $dst &>>$LOG_FILE
	else
		cp -v "$url" $dst &>>$LOG_FILE
	fi
}

do_clean_file() {
	[ $# -eq 1 ] || die "${FUNCNAME}: unexpected number of params"
	local filename=$1

	local src="${TMP_DIR}/${filename}"
	log "clearing $src"    
	rm -f $src &>>$LOG_FILE
}

do_clean_dir() {
	[ $# -eq 1 ] || die "${FUNCNAME}: unexpected number of params"
	local filename=$1

	local src="${TMP_DIR}/${filename}"
	log "clearing $src"
	if [ -d $src ]; then
		[ "$src" == "/" ] && die "$src is root!!!"
		rm -rf $src &>>$LOG_FILE
		return $?
	fi
	return 0
}

do_install_www() {
	[ $# -eq 1 ] || die "${FUNCNAME}: unexpected number of params"
	local directory=$1

	local src="${TMP_DIR}/${directory}"
	local dst="${WWW_DIR}/${directory}"
	[ ! -d $src ] && log "$src not found or not a directory!" && return 1
	[ -e $dst ] && log "$dst exists!" && return 1

	log "copying $src to $dst, chown root"
	{ cp -rp $src $dst && chown -R root:root $dst
	} &>>$LOG_FILE
}

do_unpackage() {
	[ $# -eq 1 ] || die "${FUNCNAME}: unexpected number of params"
	local tgzfile=$1
	
	local src="${TMP_DIR}/${tgzfile}"
	[ ! -f $src ] && log "${FUNCNAME}: $src not found!" && return 1

	log "unpackaging $tgzfile"
	tar -zxf $src -C $TMP_DIR &>>$LOG_FILE
}

## steps
install_deps() {
	step "Installing dependencies"
	
	apt-get update >/dev/null 2>>$LOG_FILE
	[ $? -ne 0 ] && step_err "running apt-get update" && return 1

	DEBIAN_FRONTEND=noninteractive apt-get install -y $DEPS &>>$LOG_FILE
	[ $? -ne 0 ] && step_err "installing dependencies" && return 1

	step_ok
}

install_webapp() {
	step "Downloading and installing web application"

	user_exists $WWW_USER
	[ $? -ne 0 ] && step_err "$WWW_USER not exists" && return 1

	## download
	do_download "$DOWNLOAD_URI" ${NAME}.tgz
	[ $? -ne 0 ] && step_err && return 1
	do_unpackage ${NAME}.tgz
	[ $? -ne 0 ] && step_err && return 1
	do_clean_file ${NAME}.tgz

	## deploy
	do_install_www ${NAME}
	[ $? -ne 0 ] && step_err && return 1
	do_clean_dir ${NAME}

	log "setting storage perms"
	## setup storage perms
	[ ! -d "${WWW_DIR}/${NAME}/storage" ] && \
		step_err "can't found ${WWW_DIR}/${NAME}/storage" && return 1
	[ ! -d "${WWW_DIR}/${NAME}/bootstrap/cache" ] && \
		step_err "can't found ${WWW_DIR}/${NAME}/bootstrap/cache" && return 1
	{ chown -R www-data:www-data ${WWW_DIR}/${NAME}/storage \
		${WWW_DIR}/${NAME}/bootstrap/cache 
	} &>>$LOG_FILE
	[ $? -ne 0 ] && step_err "setting storage permissions" && return 1

	step_ok
}

setup_webapp() {
	step "Initializing web application"

	## initialice web app
	local aexit=0
	pushd ${WWW_DIR}/${NAME} >/dev/null
	php artisan key:generate &>>$LOG_FILE
	[ $? -ne 0 ] && aexit=$?
	ADMIN_NAME="${ADMIN_NAME}" ADMIN_USERNAME="${ADMIN_USERNAME}" ADMIN_PASSWORD="${ADMIN_PASSWORD}" \
		php artisan db:seed --force &>>$LOG_FILE
	[ $? -ne 0 ] && aexit=$?
	popd >/dev/null
	[ $aexit -ne 0 ] && step_err "setting up key and db" && return 1

	envfile=${WWW_DIR}/${NAME}/.env
	echo "APP_URL=http://localhost/${NAME}" >>$envfile
	echo "ARCHIVE_HOST=${ARCHIVE_HOST}" >>$envfile
	echo "ARCHIVE_PORT=${ARCHIVE_PORT}"  >>$envfile
	echo "ARCHIVE_DATABASE=${ARCHIVE_DATABASE}" >>$envfile
	echo "ARCHIVE_USERNAME=${ARCHIVE_USERNAME}" >>$envfile
	echo "ARCHIVE_PASSWORD=${ARCHIVE_PASSWORD}" >>$envfile

	step_ok
}

config_apache2() {
	step "Enable apache2 required modules and create alias"

	log "writing config $APACHE_DIR/conf-available/${NAME}.conf"
	{ cat > $APACHE_DIR/conf-available/${NAME}.conf <<EOF
Alias /${NAME} ${WWW_DIR}/${NAME}/public

<Directory ${WWW_DIR}/${NAME}>
    Options -Indexes +FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
EOF
	} &>>$LOG_FILE
	[ $? -ne 0 ] && step_err "writing apache2 config" && return 1

	log "enable mod rewrite"
	a2enmod rewrite &>>$LOG_FILE
	[ $? -ne 0 ] && step_err "enabling mod rewrite" && return 1
	log "enable config ${NAME}"
	a2enconf ${NAME} &>>$LOG_FILE
	[ $? -ne 0 ] && step_err "enabling config ${NAME}" && return 1

	step_ok
}

## main process
install_deps || die "Show $LOG_FILE"
install_webapp || die "Show $LOG_FILE"
setup_webapp || die "Show $LOG_FILE"
config_apache2 || die "Show $LOG_FILE"

echo
echo "Installation success!. You can see $LOG_FILE for details."
echo "  - Check env file:  $WWW_DIR/${NAME}/.env"
echo "  - Reload your apache2:  systemctl reload apache2"
