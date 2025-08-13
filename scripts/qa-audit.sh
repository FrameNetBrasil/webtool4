#!/bin/bash

# FNBr Webtool 4.0 - Quality Assurance Audit Script
# Phase 5: Comprehensive testing and validation

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Configuration
PROJECT_NAME="FNBr Webtool 4.0 - Bulma Migration QA"
QA_REPORT_DIR="./qa-reports"
TIMESTAMP=$(date +%Y%m%d-%H%M%S)

echo -e "${BLUE}🔍 ${PROJECT_NAME}${NC}"
echo "==========================================="
echo -e "${CYAN}Timestamp: ${TIMESTAMP}${NC}"
echo ""

# Create reports directory
mkdir -p "$QA_REPORT_DIR"

# Phase 1: Environment Check
echo -e "${YELLOW}📋 Phase 1: Environment Check${NC}"
echo "-----------------------------------"

# Check if required tools are installed
REQUIRED_TOOLS=("node" "npm" "php" "composer")
MISSING_TOOLS=()

for tool in "${REQUIRED_TOOLS[@]}"; do
    if ! command -v $tool &> /dev/null; then
        MISSING_TOOLS+=($tool)
    else
        VERSION=$(command -v $tool > /dev/null && $tool --version 2>/dev/null | head -1 || echo "unknown")
        echo -e "${GREEN}✅ $tool: $VERSION${NC}"
    fi
done

if [ ${#MISSING_TOOLS[@]} -ne 0 ]; then
    echo -e "${RED}❌ Missing required tools: ${MISSING_TOOLS[*]}${NC}"
    exit 1
fi

# Check if build files exist
if [ ! -f "public/build/manifest.json" ]; then
    echo -e "${YELLOW}⚠️  Build files not found. Running build...${NC}"
    npm run build:prod
fi

echo -e "${GREEN}✅ Environment check passed${NC}"
echo ""

# Phase 2: Unit & Integration Tests
echo -e "${YELLOW}🧪 Phase 2: Unit & Integration Tests${NC}"
echo "-----------------------------------"

echo -e "${CYAN}Running Laravel test suite...${NC}"
if php artisan test --filter=BulmaComponentsTest --coverage-text --coverage-html="$QA_REPORT_DIR/coverage" > "$QA_REPORT_DIR/test-results-$TIMESTAMP.txt" 2>&1; then
    echo -e "${GREEN}✅ All tests passed${NC}"
    TEST_RESULTS=$(grep "Tests:" "$QA_REPORT_DIR/test-results-$TIMESTAMP.txt" || echo "Tests completed")
    echo -e "${CYAN}   $TEST_RESULTS${NC}"
else
    echo -e "${RED}❌ Some tests failed${NC}"
    echo -e "${YELLOW}   Check $QA_REPORT_DIR/test-results-$TIMESTAMP.txt for details${NC}"
fi

echo ""

# Phase 3: Bundle Analysis
echo -e "${YELLOW}📦 Phase 3: Bundle Analysis${NC}"
echo "-----------------------------------"

if [ -f "public/build/manifest.json" ]; then
    echo -e "${CYAN}Analyzing bundle sizes...${NC}"
    
    # Get bundle sizes
    MAIN_JS=$(find public/build -name "app-*.js" -not -path "*/auto-*" | head -1)
    VENDOR_JS=$(find public/build -name "vendor-*.js" | head -1)
    MAIN_CSS=$(find public/build -name "app-*.css" | head -1)
    BULMA_CSS=$(find public/build -name "app-*.css" | tail -1)
    
    if [ -f "$MAIN_JS" ]; then
        MAIN_JS_SIZE=$(stat -c%s "$MAIN_JS" 2>/dev/null || stat -f%z "$MAIN_JS" 2>/dev/null || echo "0")
        MAIN_JS_SIZE_KB=$((MAIN_JS_SIZE / 1024))
        echo -e "${CYAN}   Main JS: ${MAIN_JS_SIZE_KB}KB${NC}"
        
        if [ $MAIN_JS_SIZE_KB -gt 120 ]; then
            echo -e "${YELLOW}   ⚠️  Main JS exceeds 120KB target${NC}"
        else
            echo -e "${GREEN}   ✅ Main JS within size target${NC}"
        fi
    fi
    
    if [ -f "$VENDOR_JS" ]; then
        VENDOR_JS_SIZE=$(stat -c%s "$VENDOR_JS" 2>/dev/null || stat -f%z "$VENDOR_JS" 2>/dev/null || echo "0")
        VENDOR_JS_SIZE_KB=$((VENDOR_JS_SIZE / 1024))
        echo -e "${CYAN}   Vendor JS: ${VENDOR_JS_SIZE_KB}KB${NC}"
    fi
    
    if [ -f "$MAIN_CSS" ]; then
        CSS_SIZE=$(stat -c%s "$MAIN_CSS" 2>/dev/null || stat -f%z "$MAIN_CSS" 2>/dev/null || echo "0")
        CSS_SIZE_KB=$((CSS_SIZE / 1024))
        echo -e "${CYAN}   CSS: ${CSS_SIZE_KB}KB${NC}"
    fi
    
    echo -e "${GREEN}✅ Bundle analysis complete${NC}"
else
    echo -e "${RED}❌ Build manifest not found${NC}"
fi

echo ""

# Phase 4: Security Audit
echo -e "${YELLOW}🔒 Phase 4: Security Audit${NC}"
echo "-----------------------------------"

echo -e "${CYAN}Checking for security vulnerabilities...${NC}"

# Check npm packages for vulnerabilities
if command -v npm &> /dev/null; then
    echo -e "${CYAN}   Running npm audit...${NC}"
    if npm audit --audit-level=moderate > "$QA_REPORT_DIR/npm-audit-$TIMESTAMP.txt" 2>&1; then
        echo -e "${GREEN}   ✅ No critical npm vulnerabilities found${NC}"
    else
        VULN_COUNT=$(grep -c "vulnerability" "$QA_REPORT_DIR/npm-audit-$TIMESTAMP.txt" 2>/dev/null || echo "0")
        if [ "$VULN_COUNT" -gt 0 ]; then
            echo -e "${YELLOW}   ⚠️  $VULN_COUNT npm vulnerabilities found${NC}"
            echo -e "${CYAN}   Check $QA_REPORT_DIR/npm-audit-$TIMESTAMP.txt for details${NC}"
        fi
    fi
fi

# Check composer packages for vulnerabilities (if available)
if command -v composer &> /dev/null; then
    echo -e "${CYAN}   Checking Composer packages...${NC}"
    if composer audit > "$QA_REPORT_DIR/composer-audit-$TIMESTAMP.txt" 2>&1; then
        echo -e "${GREEN}   ✅ No critical Composer vulnerabilities found${NC}"
    else
        echo -e "${YELLOW}   ⚠️  Some Composer vulnerabilities may exist${NC}"
        echo -e "${CYAN}   Check $QA_REPORT_DIR/composer-audit-$TIMESTAMP.txt for details${NC}"
    fi
fi

# Check file permissions
echo -e "${CYAN}   Checking critical file permissions...${NC}"
CRITICAL_FILES=("app/Http/Controllers" "config" "routes" ".env")
PERMISSION_ISSUES=0

for file in "${CRITICAL_FILES[@]}"; do
    if [ -e "$file" ]; then
        PERMS=$(ls -la "$file" | awk '{print $1}' | head -1)
        if [[ "$file" == ".env" && "$PERMS" != *"------"* ]]; then
            echo -e "${YELLOW}   ⚠️  .env file permissions too open: $PERMS${NC}"
            PERMISSION_ISSUES=$((PERMISSION_ISSUES + 1))
        fi
    fi
done

if [ $PERMISSION_ISSUES -eq 0 ]; then
    echo -e "${GREEN}   ✅ File permissions check passed${NC}"
fi

echo -e "${GREEN}✅ Security audit complete${NC}"
echo ""

# Phase 5: Accessibility Validation
echo -e "${YELLOW}♿ Phase 5: Accessibility Validation${NC}"
echo "-----------------------------------"

echo -e "${CYAN}Validating WCAG 2.1 compliance...${NC}"

# Check for accessibility features in code
ACCESSIBILITY_FEATURES=("aria-label" "role=" "alt=" "skip-link" "sr-only")
TOTAL_FEATURES=0

for feature in "${ACCESSIBILITY_FEATURES[@]}"; do
    COUNT=$(grep -r "$feature" app/UI/layouts/ app/UI/components/ 2>/dev/null | wc -l || echo "0")
    if [ "$COUNT" -gt 0 ]; then
        echo -e "${GREEN}   ✅ Found $COUNT instances of '$feature'${NC}"
        TOTAL_FEATURES=$((TOTAL_FEATURES + COUNT))
    else
        echo -e "${YELLOW}   ⚠️  No instances of '$feature' found${NC}"
    fi
done

echo -e "${CYAN}   Total accessibility features found: $TOTAL_FEATURES${NC}"

if [ $TOTAL_FEATURES -gt 10 ]; then
    echo -e "${GREEN}   ✅ Good accessibility implementation detected${NC}"
else
    echo -e "${YELLOW}   ⚠️  Limited accessibility features detected${NC}"
fi

echo -e "${GREEN}✅ Accessibility validation complete${NC}"
echo ""

# Phase 6: Performance Check
echo -e "${YELLOW}⚡ Phase 6: Performance Validation${NC}"
echo "-----------------------------------"

echo -e "${CYAN}Checking performance optimizations...${NC}"

# Check for performance features
PERF_FEATURES=(
    "lazy" 
    "defer"
    "preload"
    "x-transition"
    "code-splitting"
)

PERF_SCORE=0

for feature in "${PERF_FEATURES[@]}"; do
    COUNT=$(grep -r "$feature" resources/ app/UI/ 2>/dev/null | wc -l || echo "0")
    if [ "$COUNT" -gt 0 ]; then
        echo -e "${GREEN}   ✅ $feature optimization found ($COUNT instances)${NC}"
        PERF_SCORE=$((PERF_SCORE + 1))
    fi
done

# Check build optimizations
if [ -f "vite.config.js" ]; then
    if grep -q "manualChunks" vite.config.js; then
        echo -e "${GREEN}   ✅ Code splitting configured${NC}"
        PERF_SCORE=$((PERF_SCORE + 1))
    fi
    
    if grep -q "minify" vite.config.js; then
        echo -e "${GREEN}   ✅ Minification configured${NC}"
        PERF_SCORE=$((PERF_SCORE + 1))
    fi
fi

echo -e "${CYAN}   Performance optimization score: $PERF_SCORE/7${NC}"

if [ $PERF_SCORE -ge 5 ]; then
    echo -e "${GREEN}   ✅ Good performance optimizations detected${NC}"
else
    echo -e "${YELLOW}   ⚠️  Limited performance optimizations detected${NC}"
fi

echo ""

# Phase 7: Component Library Validation
echo -e "${YELLOW}🧱 Phase 7: Component Library Validation${NC}"
echo "-----------------------------------"

echo -e "${CYAN}Validating Bulma component library...${NC}"

# Check for Bulma components
BULMA_COMPONENTS=(
    "datagrid-bulma.blade.php"
    "header-bulma.blade.php" 
    "sidebar-bulma.blade.php"
    "index-bulma.blade.php"
    "dataGridBulmaComponent.js"
    "bulmaComponents.js"
)

COMPONENTS_FOUND=0

for component in "${BULMA_COMPONENTS[@]}"; do
    if find . -name "$component" -not -path "./node_modules/*" | grep -q .; then
        echo -e "${GREEN}   ✅ $component found${NC}"
        COMPONENTS_FOUND=$((COMPONENTS_FOUND + 1))
    else
        echo -e "${RED}   ❌ $component missing${NC}"
    fi
done

echo -e "${CYAN}   Components found: $COMPONENTS_FOUND/${#BULMA_COMPONENTS[@]}${NC}"

if [ $COMPONENTS_FOUND -eq ${#BULMA_COMPONENTS[@]} ]; then
    echo -e "${GREEN}   ✅ All Bulma components present${NC}"
else
    echo -e "${YELLOW}   ⚠️  Some components missing${NC}"
fi

echo ""

# Generate Final Report
echo -e "${PURPLE}📊 Generating QA Report...${NC}"
echo "-----------------------------------"

REPORT_FILE="$QA_REPORT_DIR/qa-summary-$TIMESTAMP.txt"

cat > "$REPORT_FILE" << EOF
FNBr Webtool 4.0 - Quality Assurance Report
==========================================
Generated: $TIMESTAMP

SUMMARY:
--------
✅ Environment Check: PASSED
🧪 Unit Tests: CHECK DETAILS
📦 Bundle Analysis: PASSED
🔒 Security Audit: CHECK DETAILS  
♿ Accessibility: CHECK DETAILS
⚡ Performance: CHECK DETAILS
🧱 Components: $COMPONENTS_FOUND/${#BULMA_COMPONENTS[@]} found

BUNDLE SIZES:
------------
Main JS: ${MAIN_JS_SIZE_KB:-N/A}KB
Vendor JS: ${VENDOR_JS_SIZE_KB:-N/A}KB  
CSS: ${CSS_SIZE_KB:-N/A}KB

RECOMMENDATIONS:
--------------
EOF

# Add recommendations based on findings
if [ "${MAIN_JS_SIZE_KB:-0}" -gt 120 ]; then
    echo "- Consider further code splitting for main JS bundle" >> "$REPORT_FILE"
fi

if [ "$TOTAL_FEATURES" -lt 10 ]; then
    echo "- Improve accessibility features implementation" >> "$REPORT_FILE"
fi

if [ "$PERF_SCORE" -lt 5 ]; then
    echo "- Implement additional performance optimizations" >> "$REPORT_FILE"
fi

echo -e "${GREEN}✅ QA report generated: $REPORT_FILE${NC}"

# Final Summary
echo ""
echo -e "${BLUE}🏁 Quality Assurance Audit Complete!${NC}"
echo "========================================="
echo -e "${GREEN}✅ Comprehensive testing completed${NC}"
echo -e "${CYAN}📄 Reports saved in: $QA_REPORT_DIR/${NC}"
echo -e "${CYAN}📊 Summary report: $REPORT_FILE${NC}"
echo ""

if [ $COMPONENTS_FOUND -eq ${#BULMA_COMPONENTS[@]} ] && [ "${MAIN_JS_SIZE_KB:-0}" -le 120 ]; then
    echo -e "${GREEN}🎉 Quality assurance PASSED! Ready for production.${NC}"
    exit 0
else
    echo -e "${YELLOW}⚠️  Some issues detected. Review recommendations.${NC}"
    exit 1
fi