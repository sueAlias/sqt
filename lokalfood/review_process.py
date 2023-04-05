#C:\Users\Suraya\AppData\Local\Programs\Python\Python38\python.exe

import sys
from textblob import TextBlob
from textblob import Word
import random

txt = sys.argv[1]
#txt = "can give a try, love the sambal not so spicy"
feedback = TextBlob(txt)
reviewPolarity = TextBlob(txt).sentiment.polarity
reviewSubjectivity = TextBlob(txt).sentiment.subjectivity
#out = txt + "<br>" + str(feedback.sentiment)
out = feedback.sentiment.polarity
print(out)

#nouns = list()
# for word, tag in feedback.tags:
#     if tag == 'NN': 
#         nouns.append(word.lemmatize())
# 
# #print ("This feedback is about...")
# sumWord=[]
# for item in random.sample(nouns, 1):
#     word = Word(item)
#     #out = str(word.pluralize())
#     sumWord.append(str(word.pluralize()))

#out = "Text : "  + txt + "<br>" + str(feedback.sentiment) + "<br>" + "Summarized Word : " + "<br>" + str(sumWord)
#print (out)
    
out = str(reviewPolarity) + " " + str(reviewSubjectivity)
print(out)


