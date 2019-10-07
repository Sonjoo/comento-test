**본 문서는 코딩 과제용이나, 자유롭게 사용가능합니다**
**그 어떤 질문 및 질타는 감사히 받습니다.**


설치 방법 
=============

전제: php 와 mysql, composer 가 설치 되어있다는 전제에서 시작합니다. 
--------

1. composer install
2. composer dumpautoload
3. .env.example 을 자신의 환경에 맞게 변경 
4. .env 내의 QUEUE_CONNECTION=database 로 설정해야 queue 가 돌아갑니다. 
5. php artisan migrate 실행 
6. php artisan serve 를 실행하면 화면을 보실수 있습니다. 
7. admin계정 설정은 별도로 없으니, 신규 계정을 tinker 로 변경하시는 것을 추천드립니다. 

빠르게 데이터를 바로 돌려보고 싶으신 경우
------
1. 위의 사항이 끝났다면 php artisan db:seed 를 실행해 주세요. 
2. 현재 설정은 10만개 이상의 데이터가 돌아가기 때문에 간단한 확인을 위해서는 database\seeds\DatabaseSeeder.php 를 수정해주세요 


이 프로그램을 통해 할 수 있는것. 
-----
1. prefix가 걸려있는 16자리의 무작위 영문 대소문자 + 숫자를 만들 수 있습니다. ('admin'계정)
2. Coupon 그룹을 설정하고 그룹별 생성 및 검색이 가능합니다('admin'계정)
3. 로그인 및 회원가입이 가능합니다. 
4. 쿠폰 사용이 가능합니다. (일반 유저) 

라라벨 뉴비라면 알게 될 수 도 있는 것. 
-------
1. Seeder의 사용 
2. 라라벨 기본제공 Auth 사용 방법 
3. 기본적인 pw암호화 
4. 라라벨 Queue의 사용
...
