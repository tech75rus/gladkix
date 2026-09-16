# Проверка True Color (должен показать плавный градиент)
awk 'BEGIN{
    s="/\\/\\/\\/\\/\\"; s=s s s s s s s s;
    for (colnum = 0; colnum<77; colnum++) {
        r = 255-(colnum*255/76);
        g = (colnum*510/76);
        b = (colnum*255/76);
        if (g>255) g = 510-g;
        printf "\033[48;2;%d;%d;%dm", r,g,b;
        printf "\033[30m%s\033[0m", substr(s,colnum+1,1);
    }
    printf "\n";
}'

for i in {0..255}; 
do printf "\033[38;5;%dm███ %3d " $i $i; 
    [ $((($i + 1) % 8)) -eq 0 ] && echo; 
done; 
echo -e "\033[0m"