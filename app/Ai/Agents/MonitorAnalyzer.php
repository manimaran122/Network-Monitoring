<?php

namespace App\Ai\Agents;

use App\Ai\Tools\FetchMonitorStatus;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
// use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

class MonitorAnalyzer implements Agent, Conversational, HasTools //, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are an expert system monitoring analyst with deep knowledge of server infrastructure, 
                network monitoring, and system health analysis. Your role is to:
                
                1. Analyze monitoring data and identify patterns
                2. Assess system health and performance metrics
                3. Provide actionable recommendations for improvements
                4. Identify potential issues before they become critical
                5. Explain technical concepts in clear, understandable language
                
                Always provide specific, actionable insights based on the data available.';
    }

    /**
     * Get the list of messages comprising the conversation so far.
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new FetchMonitorStatus,
        ];
    }

    /*
    public function schema(JsonSchema $schema): array
    {
        return [
            'status' => $schema->string()
                ->enum(['healthy', 'warning', 'critical', 'unknown'])
                ->required()
                ->description('Overall system health status'),
            
            'health_score' => $schema->integer()
                ->min(0)
                ->max(100)
                ->required()
                ->description('Overall health score from 0-100'),
            
            'summary' => $schema->string()
                ->required()
                ->description('Brief summary of the analysis'),
            
            'insights' => $schema->array()
                ->items($schema->string())
                ->required()
                ->description('Key insights from the monitoring data'),
            
            'recommendations' => $schema->array()
                ->items($schema->string())
                ->required()
                ->description('Actionable recommendations for improvement'),
            
            'priority_issues' => $schema->array()
                ->items($schema->object([
                    'severity' => $schema->string()->enum(['low', 'medium', 'high', 'critical']),
                    'description' => $schema->string(),
                    'suggested_action' => $schema->string(),
                ]))
                ->description('List of priority issues that need attention'),
        ];
    }
    */

    /**
     * Get the AI provider to use.
     */
    public function provider(): string
    {
        return Lab::Gemini->value;
    }

    /**
     * Get the model to use.
     */
    public function model(): string
    {
        return 'gemini-2.5-flash';
    }
}
