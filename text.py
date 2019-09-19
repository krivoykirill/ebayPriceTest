from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.ensemble import RandomForestClassifier
from sklearn.pipeline import Pipeline
 
 
texts = ['текст номер один', 'текст номер два', 'комьютеры в лингвистике', 'компьютеры и обработка текстов']
texts_labels = [1, 1, 0, 0]
 
text_clf = Pipeline([
                     ('tfidf', TfidfVectorizer()),
                     ('clf', RandomForestClassifier())
                     ])
 
text_clf.fit(texts, texts_labels)
 
res = text_clf.predict(['текст номер три'])
print(res)  # [1]
