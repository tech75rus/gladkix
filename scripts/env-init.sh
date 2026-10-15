set -euo pipefail

# ────── 256-цветная палитра ──────
GREEN='\033[38;5;85m'      # яркий зелёный
BLUE='\033[38;5;39m'       # насыщенный синий
ORANGE='\033[38;5;209m'    # оранжевый (для предупреждений)
PURPLE='\033[38;5;141m'    # фиолетовый (для заголовков)
GRAY='\033[38;5;244m'      # серый (для secondary)
RED='\033[38;5;160m'       # ярко-красный
BOLD='\033[1m'
NC='\033[0m'

# ────── Пути к файлам переменных окружения ──────
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
BACKEND_DIR="$PROJECT_ROOT/portfolio/backend"
FRONTEND_DIR="$PROJECT_ROOT/portfolio/frontend"

EXAMPLE_ROOT_ENV="$PROJECT_ROOT/.env.example"
DEV_ROOT_ENV="$PROJECT_ROOT/.env.dev"
PROD_ROOT_ENV="$PROJECT_ROOT/.env.prod"

EXAMPLE_BACKEND_ENV="$PROJECT_ROOT/portfolio/backend/.env.example"
DEV_BACKEND_ENV="$PROJECT_ROOT/portfolio/backend/.env.dev"
PROD_BACKEND_ENV="$PROJECT_ROOT/portfolio/backend/.env.prod"

EXAMPLE_FRONTEND_ENV="$PROJECT_ROOT/portfolio/frontend/.env.example"
PROD_FRONTEND_ENV="$PROJECT_ROOT/portfolio/frontend/.env"


# ────── Информационные функции ──────
success() { echo -e "${GREEN}✅ $1${NC}"; }
info()    { echo -e "${BLUE}ℹ️  $1${NC}"; }
warn()    { echo -e "${ORANGE}⚠️  $1${NC}"; }
error()   { echo -e "${RED}❌ $1${NC}" >&2; }
header()  { echo -e "${BOLD}${PURPLE}═════════ $1 ═════════${NC}"; }
dim()     { echo -e "${GRAY}ℹ️  $1${NC}"; }

generate_data() {
  local data='CHANGE_ME'
  local secure_data='SECURE_DATA'
}

check_variable_env() {
  info "Проверка переменной окружения"
}

create_env_file() {
  local exampleEnv="$1"
  local env="$2"

  info "Создаем файл .env.dev"
  cp "$exampleEnv" "$env"

  while IFS= read -r line || [[ -n "$line" ]]; do

      if [[ "$line" == *"CHANGE_ME"* ]]; then
          value=$(openssl rand -hex 32)
          echo "${line//CHANGE_ME/$value}" >> $env
      fi

      if [[ "$line" == *"SECURE_DATA"* ]]; then
          secure_value=$(openssl rand -hex 64)
          echo "${line//SECURE_DATA/$secure_value}" >> $env
      fi

  done < "$exampleEnv"


  success "Файл $env создан на основе $exampleEnv"
}

# ────── Создание переменных окружения ──────
init_env_file() {
  local exampleEnv="$1"
  local env="$2"
  local mode="$3"

  if [ ! -f "$env" ]; then
    warn "Файл $env не найден!"
    create_env_file "$exampleEnv" "$env"
  else
    info "Файл $env уже существует. Пропускаем создание."
    check_variable_env
    return
  fi
}

MODE=${1:-dev}
case $MODE in
  dev)
    echo ""
    header "Это dev разработка"
    init_env_file $EXAMPLE_ROOT_ENV $DEV_ROOT_ENV dev
    init_env_file $EXAMPLE_BACKEND_ENV $DEV_BACKEND_ENV dev
    header "Это dev разработка"
    echo ""
    ;;
  prod)
    header "Это prod разработка"
    init_env_file $EXAMPLE_ROOT_ENV $PROD_ROOT_ENV prod
    ;;
  *)
    warn "Аргумент $MODE должен быть 'dev' или 'prod'."
    exit 1
    ;;
esac

