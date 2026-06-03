<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class SalesCoach implements Agent, Conversational, HasTools
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return <<<PROMPT
        You are an expert Sales Coach.

        Analyze sales conversations and respond ONLY with a valid JSON object, no extra text, no markdown, no code blocks.

        The JSON must follow this exact structure:
        {
            "customer_needs": ["need 1", "need 2"],
            "customer_objections": ["objection 1", "objection 2"],
            "salesperson_strengths": ["strength 1", "strength 2"],
            "salesperson_weaknesses": ["weakness 1", "weakness 2"],
            "suggested_responses": ["response 1", "response 2"],
            "closing_probability": 75,
            "sales_score": 8
        }

        Rules:
        - closing_probability is an integer between 0 and 100
        - sales_score is a number between 0 and 10
        - All arrays must have at least one item
        - Do NOT wrap the JSON in markdown or code blocks
        - Return ONLY the JSON object, nothing else
        PROMPT;
    }

    /**
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [];
    }
}
