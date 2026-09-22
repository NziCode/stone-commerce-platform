SET NAMES utf8mb4;
START TRANSACTION;
DELETE FROM posts WHERE JSON_UNQUOTE(JSON_EXTRACT(slug,'$.en')) LIKE 'pedrini-news-%';
INSERT INTO posts (user_id,title,slug,excerpt,content,og_image,reading_time,status,published_at,created_at,updated_at)
WITH RECURSIVE n AS (SELECT 1 id UNION ALL SELECT id+1 FROM n WHERE id<30)
SELECT 1,
JSON_OBJECT('fa',CONCAT('خبر تخصصی سنگ شماره ',id),'en',CONCAT('Pedrini stone industry news ',id),'it',CONCAT('Notizie Pedrini sulla pietra ',id),'ar',CONCAT('أخبار بيدريني للحجر ',id),'hi',CONCAT('पेडरिनी पत्थर समाचार ',id),'zh',CONCAT('Pedrini 石材新闻 ',id),'tr',CONCAT('Pedrini taş haberi ',id)),
JSON_OBJECT('fa',CONCAT('pedrini-news-',LPAD(id,2,'0')),'en',CONCAT('pedrini-news-',LPAD(id,2,'0')),'it',CONCAT('pedrini-news-',LPAD(id,2,'0')),'ar',CONCAT('pedrini-news-',LPAD(id,2,'0')),'hi',CONCAT('pedrini-news-',LPAD(id,2,'0')),'zh',CONCAT('pedrini-news-',LPAD(id,2,'0')),'tr',CONCAT('pedrini-news-',LPAD(id,2,'0'))),
JSON_OBJECT('fa','گزارش اختصاصی درباره فناوری و فرآوری سنگ طبیعی.','en','Original editorial report about natural stone processing technology.','it','Approfondimento originale sulla lavorazione della pietra naturale.','ar','تقرير أصلي عن تكنولوجيا معالجة الحجر الطبيعي.','hi','प्राकृतिक पत्थर प्रसंस्करण पर मूल रिपोर्ट।','zh','关于天然石材加工技术的原创报道。','tr','Doğal taş işleme teknolojisi hakkında özgün rapor.'),
JSON_OBJECT('fa','<p>این گزارش اختصاصی، فناوری، کنترل کیفیت، بهره‌وری و نگهداری خطوط فرآوری سنگ را بررسی می‌کند.</p>','en','<p>This original report reviews technology, quality control, productivity and maintenance in stone-processing lines.</p>','it','<p>Questo approfondimento originale analizza tecnologia, qualità, produttività e manutenzione.</p>','ar','<p>يستعرض هذا التقرير التكنولوجيا والجودة والإنتاجية والصيانة.</p>','hi','<p>यह रिपोर्ट तकनीक, गुणवत्ता, उत्पादकता और रखरखाव की समीक्षा करती है।</p>','zh','<p>本文原创介绍技术、质量、效率与维护。</p>','tr','<p>Bu özgün rapor teknoloji, kalite, verimlilik ve bakımı inceler.</p>'),
CONCAT('images/news/',CASE MOD(id,6) WHEN 1 THEN 'quarry-cutting.png' WHEN 2 THEN 'showroom-slabs.png' WHEN 3 THEN 'polishing-line.png' WHEN 4 THEN 'architect-selection.png' WHEN 5 THEN 'water-recycling.png' ELSE 'export-shipping.png' END),4,'published',DATE_SUB(NOW(),INTERVAL (30-id) DAY),NOW(),NOW() FROM n;
COMMIT;
