if [ ! -d ./vendor ]; then
  mkdir ./vendor
fi
wget -O ./vendor/SplClassLoader.php https://raw.github.com/gist/221634/2bc31f04b0ed0ef70daab68516c8d17ba0753f5e/SplClassLoader.php
