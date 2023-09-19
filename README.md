# kma-test-project
Тестовое задание на позицию разработчик PHP

## Перед тем, как начать

### Установка docker

#### Linux

1. [Install docker](https://docs.docker.com/engine/install/)
2. [Post-install steps](https://docs.docker.com/engine/install/linux-postinstall/)
3. [Install docker-compose](https://docs.docker.com/compose/install/)

#### Windows/macOS

Установить [Docker Desktop](https://docs.docker.com/desktop/)

##### Windows WSL

Для оптимальной производительности [рекомендуется](https://docs.docker.com/desktop/windows/wsl/#best-practices) хранить файлы в файловой системе WSL2.

[Установка WSL2](https://learn.microsoft.com/ru-ru/windows/wsl/install)

Внутри Ubuntu необходимо установить следующие пакеты:

```shell
sudo apt update && sudo apt install git php-cli
```

### Развертка проекта
### Установка traefik

Traefik - реверс-прокси с поддержкой динамической конфигурации из Docker.
Он позволит обращаться к локальным поддоменам типа [rabbit.kma.localhost](http://rabbit.kma.localhost/)...

1. Создать docker network

```shell
docker network create web
```

2. Склонировать [репозиторий](https://github.com/mediaten/traefik-v2) в произвольный каталог и выполнить `make`:

```shell
git clone https://github.com/mediaten/traefik-v2
cd traefik-v2
make
```

### Обновить /etc/hosts  (macOS)

```shell
127.0.0.1 kma.localhost
127.0.0.1 rabbit.localhost
```

Для использования образов ARM на Apple Silicon выполнить:

```shell
make cp-env
```

и изменить в файле `.env` переменную

```
BUILDPLATFORM=linux/arm64
```

### Готово!

Переходим на [kma.localhost](http://kma.localhost/) и проверяем работу. Если всё работает Вы великолепны!

## Полезные команды на каждый день

### Создание .env из шаблона .env-dist

```
make cp-env
```

### Полная установка проекта

```
make install
```

### Подъем docker окружения

```
make up
```

### Установка composer пакетов

```
make composer-install
```

## Тестирование

```
make sender
```

Берёт построчно данные из urls.txt и отправляет в очередь.
Данные в очереди можно посмотреть в [rabbit.kma.localhost](http://rabbit.kma.localhost/)


```
make receive
```
Данные берутся из очереди и кладутся в БД
Данные в БД можно посмотреть через Phpadmin - [pma.kma.localhost](http://pma.kma.localhost/)


Данные в виде таблицы доступны через веб интерфейс по адрессу: [kma.localhost](http://kma.localhost/)