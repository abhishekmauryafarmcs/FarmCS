

import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns
import plotly.express as px
import plotly.graph_objects as go
from plotly.subplots import make_subplots

import common


df=pd.read_csv("df2013.csv")

def piechart():
    # User input for state and crop
    state_name = common.state_name
    crop_name = common.crop_name

    # Filter data based on user inputs
    filtered_data = df[(df['State_Name'] == state_name) & (df['Crop'] == crop_name)]

    # Group by district to calculate total pct_production
    district_data = filtered_data.groupby('District_Name', as_index=False)['pct_production_all_Over_India'].sum()

    # Plot the pie chart
    fig = px.pie(
        district_data,
        values='pct_production_all_Over_India',
        names='District_Name',
        title=f'{crop_name} Production Percentage by District in {state_name}',
        color_discrete_sequence=px.colors.sequential.Plasma
    )

    fig.show()
    return

def Districtwise_all_crop_production():
   
   

    # User input for state
    state_name = common.state_name
    # Filter data for the selected state
    state_data = df[df['State_Name'] == state_name]

    # Group by District and Crop to get total Production
    district_crop_data = state_data.groupby(['District_Name', 'Crop'], as_index=False)['Production'].sum()

    # Sort data for better visualization
    district_crop_data = district_crop_data.sort_values('Production', ascending=False)

    # Plot the Horizontal Bar Chart
    fig = px.bar(
        district_crop_data,
        x='Production',
        y='District_Name',
        color='Crop',
        orientation='h',  # Horizontal bar chart
        title=f'Top Producing Districts for Each Crop in {state_name}',
        labels={'District_Name': 'District', 'Production': 'Production (tons)'},
        color_discrete_sequence=px.colors.qualitative.Set2
    )

    # Update layout to improve visualization
    fig.update_layout(
        xaxis_title="Production",
        yaxis_title="District",
        legend_title="Crop",
        yaxis=dict(categoryorder="total ascending")  # Order districts by production
    )
    #print("fun called")

    fig.show()

def Sunburst():
    # User input for state
    state_name = common.state_name # Example: 'Maharashtra'

    # Filter data for the selected state
    state_data = df[df['State_Name'] == state_name]

    # Aggregate production by Season and Crop
    seasonal_data = state_data.groupby(['Season', 'Crop'], as_index=False)['Production'].sum()

    # Add State_Name as a constant column for hierarchy
    seasonal_data['State'] = state_name

    # Plot the Sunburst Chart
    fig = px.sunburst(
        seasonal_data,
        path=['State', 'Season', 'Crop'],  # Hierarchy: State → Season → Crop
        values='Production',              # Size of each segment
        color='Season',                   # Color by season
        title=f'Seasonal Analysis of Crop Production in {state_name}',
        color_discrete_sequence=px.colors.qualitative.Dark2
    )

    # Show the chart
    fig.show()

def treemap():
    import pandas as pd
    import plotly.express as px

    # Select the state you want to analyze
    state_name = common.state_name
    state_data = df[df["State_Name"] == state_name]

    # Create the treemap
    fig = px.treemap(
        state_data,
        path=["District_Name", "Crop"],  # Hierarchy: District -> Crop
        values="Area",  # Use 'Area' as the size of the treemap blocks
        title=f"Crop Diversity in {state_name}",
        color="Area",  # Use 'Area' to determine color
        color_continuous_scale="Viridis",  # Choose a color scale
    )

    # Show the plot
    fig.show()


def particular_crop_bar_chart():

    # Group the data by State and calculate the average of pct_production_all_Over_India
    state_data = df.groupby('State_Name', as_index=False).agg(
        avg_pct_production=('pct_production_all_Over_India', 'mean')  # Aggregating as mean
    )

    # Create a bar chart using Plotly
    fig = px.bar(
        state_data,
        x='State_Name',
        y='avg_pct_production',
        title="Average % Production by State",
        labels={'avg_pct_production': 'Avg % Production', 'State_Name': 'State'},
        text='avg_pct_production'
    )

    # Customize the chart
    fig.update_traces(texttemplate='%{text:.2f}%', textposition='outside')
    fig.update_layout(xaxis_tickangle=-45)  # Rotate x-axis labels for readability

    # Show the chart
    fig.show()
