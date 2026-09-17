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
header()  { echo -e "\n${BOLD}${PURPLE}═════════ $1 ═════════${NC}"; }
dim()     { echo -e "${GRAY}ℹ️  $1${NC}"; }

# ────── Проверка переменных в файлах ──────
check_env_file() {
  local exampleEnv="$1"
  local devEnv="$2"
  if [ ! -f "$exampleEnv" ]; then
    error "Файл $exampleEnv не найден!"
  fi
  if [ ! -f "$devEnv" ]; then
    error "Файл $devEnv не найден!"
  fi

  info "Проверка переменных в файлах $exampleEnv и $devEnv"
}

MODE=${1:-dev}
case $MODE in
  dev)
    success "Это dev разработка"
    check_env_file $EXAMPLE_ROOT_ENV $DEV_ROOT_ENV
    check_env_file $EXAMPLE_BACKEND_ENV $DEV_BACKEND_ENV
    ;;
  prod)
    success "Это prod разработка"
    cp $EXAMPLE_ROOT_ENV $PROD_ROOT_ENV
    cp $EXAMPLE_BACKEND_ENV $PROD_BACKEND_ENV
    cp $EXAMPLE_FRONTEND_ENV $PROD_FRONTEND_ENV
    ;;
  *)
    warn "Аргумент $MODE должен быть 'dev' или 'prod'."
    exit 1
    ;;
esac


