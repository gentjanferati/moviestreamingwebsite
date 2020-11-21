# moviestreamingwebsite

Abstrakt Projektit
Ky projekt konsistonte në krijimin e një website me temën Online Movie Streaming, pra një website në
të cilin mund të shohësh filma apo seriale kundrejt një abonimi mujor. Gjuhët e përdorura për këtë
projekt janë HTML, CSS, JavaScript, jQuery dhe PHP. Për pjesën e databazës është përdorur Mysql.
Design i website është kryesisht i thjeshtë, por përmban të gjitha opsionet e nevojshme që kërkon një
website i tillë. Gjithashtu të gjitha faqet janë user dhe SEO friendly. Kemi përdorur slugs për të patur një
link për cdo faqe sa më të thjeshtë dhe sa më të kuptueshme si nga përdoruesit ashtu edhe nga robotat.
Ky website ka dy faqe logini ku një është për përdoruesit e thjeshtë dhe një për administratorët e faqes
të cilët janë ndarë në tre role. Për sa I përket përdoruesve, logini kryhet nëpërmjet Firebase
Authentication, i cili përvec loginit me email ofron dhe disa social logins, ku ne kemi zgjedhur Gmail dhe
Facebook. Shtimi i tyre është shumë i thjeshtë dhe nuk krijon aspak probleme.
Për loginin e administratorve kemi një login të thjeshtë me username dhe password, përmes së cilave
kryhet validimi i të dhënave përpara se të kryhet logimi.
Website ka dy faqe kryesore, një e cila është një faqe landing e cila mund të përdoret si një faqe
promovuese, dhe faqja tjetër e cila hapet gjithmon në rastet kur përdoruesi është I loguar. Kjo faqe ka
disa slidera me filmat,serialet dhe episodet e fundit. Në rastin kur përdoruesi ka disa filma të preferuar,
shfaqim dhe një slider me disa filma të preferuar të tij.
Për këtë website kemi një navigation bar i cili është një sidebar i cili aksesohet nëpërmjet hamburger
buttons. Kjo për të mos zënë hapësirë dhe për të pasur një design sa më të pastër. Gjithashtu në anën e
djathtë shfaqim dhë social buttons të cilat prezantojnë rrjetet sociale si Facebook, Instagram dhe
Twitter.
Lista e filmave dhe serialeve kanë një design të thjeshtë dhe disa buttona të cilat bëjnë të mundur
sortimin sipas filmave më të fundit, ato të cilat janë shtuar së fundmi, ato që kanë më shumë shikime
dhe ato që janë vlersuar më shumë.
Lista e episodeve ka të njëjtin format por sortimi ndodh vetem nëpërmjet episodeve që janë shtuar së
fundmi dhe ato që kanë dalë së fundmi.
Në sidebar mund të aksesojmë search, zhanret dhe profilin në rastet kur përdoruesi është loguar.
Në faqen e kërkimit shfaqen të gjitha filmat dhe serialet që kanë si keyëord fjalën apo togfjalëshin e
kërkuar.
Në faqet e zhanreve, për një zhanër të zgjedhur, shfaqen të gjitha filmat dhe serialet që i përkasin atij
zhanri.
Në faqen e profilit shfaqen informacionet për përdoruesin si emri, email, statusi I abonimit dhe data e
mbarimit të abonimit. Gjithashtu kemi disa të dhëna rreth filmave që ai ka parë dhe që ka të preferuar,
të cilat shfaqen edhe ne disa slidera më poshtë. Po ashtu nga kjo faqe aksesohet dhe faqja ku përdoruesi
mund të bëjë abonimin apo ta zgjasi atë, abonim I cili kryhet nëpërmjet pagesës me Paypal.
Për sa i përket faqeve të një filmi, seriali apo episodi, ato gjithashtu përmbajnë një design të thjeshtë,
dhe me informacione të cilat janë shtuar në databazë nëpërmjet API nga TheMovieDatabase
[http://tmdb.org/]. Imazhet që shërbehen në këto faqe apo edhe në faqet e tjera janë imazhe të cilat
aksesohen drejtpërdrejt nga ky website.
Faqja e një filmi ka si background një imazh (backdrop) nga filmi. Përmban disa informacione si titulli,
përshkrimi, zhanret, kohëzgjatja dhe vlerësimi në faqen kryesore. Gjithashtu ka disa butona në anën e
djathtë me anë të të cilave aksesohen, playeri, i cili hapet vetëm nëse përdoruesi është I loguar dhe me
një abonim aktiv, butoni doënload I cili shkarkon filmin, gjithashtu duhet një abonim aktiv, butoni
favorite I cili shton filmin tek të preferuarit, butoni trailer në të cilin mund të shohim trailerin e filmit dhe
butoni cast I cili shfaq regjizorët dhe disa prej aktorëve të filmit.
Faqja e një seriali ngjason me atë të një filmi përvec faktit që butoni play është tanimë një buton në të
cilin shfaqen sezonet, dhe pasi klikojmë një sezon, mund të shikojmë dhe të gjitha episodet e tij.
Faqja e një episodi ka më pak informacione, titullin e episodit, titullin e serialit, përshkrimin dhe ditën e
daljes së këtij episodi. Kemi buton play dhe doënload, dhe butonat next dhe previous në rast se ka, për
të kaluar tek episodi I rradhës apo I mëparshmi.
Një film apo serial kalon tek lista e të shikuarve vetëm kur përdoruesi ka parë të paktën 50% të filmit
apo të një episodi.
Për sa I përket pjesës administruese, kemi faqen kryesore në të cilën mund të shikojmë disa statistika
rreth filmave, serialeve, sezoneve, episodeve dhe përdoruesve.
Nga navigation bar mund të aksesojmë listën e filmave, listën e serialeve, listën e përdoruesve dhe
shtimin e filmave dhe serialeve nëpërmjet asaj që kemi quajtur discover. Gjithashtu butoni settings I cili
të jep mundësinë për të ndryshuar passëordin apo për të shtuar një tjetër admin [vetem kur ke rolin e
duhur].
Lista e filmave, serialeve dhe përdoruesve shfaq disa rreshta të këtyre të dhënave dhe jep mundësinë
për të ndryshuar të dhënat apo dhe për të fshirë një film apo serial. Në faqet e editimi të serialit mund
të kalojmë tek lista e sezoneve për serialin që kemi zgjedhur të editojmë. Gjithashtu nga faqja e editimit
të sezonit kemi mundësine të listojmë episodet për sezonin e zgjedhur.
Faqja më e rëndësishme e administratorit është faqja discover, e cila të mundëson të shtosh një film apo
serial me vetëm një klikim. Për këtë kemi krijuar një faqe e cila përmes API, shfaq listën e filmave apo
serialeve më popullor sipas një viti duke shtuar edhe zhanrin e zgjedhur. Gjithashtu një film apo serial
mund të kërkonhet sipas butonit search.
