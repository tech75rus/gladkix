set -euo pipefail

# ─── 256-цветная палитра ───
GREEN='\033[38;5;85m'      # яркий зелёный
BLUE='\033[38;5;39m'       # насыщенный синий
ORANGE='\033[38;5;209m'    # оранжевый (для предупреждений)
PURPLE='\033[38;5;141m'    # фиолетовый (для заголовков)
GRAY='\033[38;5;244m'      # серый (для secondary)
RED='\033[38;5;160m'       # ярко-красный
BOLD='\033[1m'
NC='\033[0m'

# ─── Семантические функции ───
success() { echo -e "${GREEN}✅ $1${NC}"; }
info()    { echo -e "${BLUE}ℹ️  $1${NC}"; }
warn()    { echo -e "${ORANGE}⚠️  $1${NC}"; }
error()   { echo -e "${RED}❌ $1${NC}" >&2; }
header()  { echo -e "\n${BOLD}${PURPLE}═════════ $1 ═════════${NC}"; }
dim()     { echo -e "${GRAY}ℹ️  $1${NC}"; }


SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"

MODE=${1:-dev}
case $MODE in
  dev)
    success "Это dev разработка"
    cp $PROJECT_ROOT/.env.example $PROJECT_ROOT/.env.dev
    cp $PROJECT_ROOT/portfolio/backend/.env.example $PROJECT_ROOT/portfolio/backend/.env.dev
    ;;
  prod)
    success "Это prod разработка"
    cp $PROJECT_ROOT/.env.example $PROJECT_ROOT/.env.prod
    cp $PROJECT_ROOT/portfolio/backend/.env.example $PROJECT_ROOT/portfolio/backend/.env.prod
    cp $PROJECT_ROOT/portfolio/frontend/.env.example $PROJECT_ROOT/portfolio/frontend/.env
    ;;
  *)
    warn "Аргумент $MODE должен быть 'dev' или 'prod'."
    exit 1
    ;;
esac


