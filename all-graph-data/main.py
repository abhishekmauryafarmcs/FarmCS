import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns
import plotly.express as px
import plotly.graph_objects as go
from plotly.subplots import make_subplots

import helper 
import common 

graph=common.graph
crop_name = common.crop_name

if(graph=="piechart"):
    helper.piechart()
elif(graph== "Crop_production_of_every_district"):
    print("Data for all the crops is available here")
    #print("elif")
    helper.Districtwise_all_crop_production()
#elif(graph== "particular_crop_bar_chart"):
 #   helper.particular_crop_bar_chart()
elif(graph== "Sunburst"):
    helper.Sunburst()
elif(graph=="treemap"):
    helper.treemap()
else:
    print("No data available! select different parameters ")